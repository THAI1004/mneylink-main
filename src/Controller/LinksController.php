<?php

namespace App\Controller;

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Detection\MobileDetect;

/**
 * @property \App\Model\Table\LinksTable $Links
 * @property \App\Model\Table\TrafficsTable $Traffics
 * @property \App\Model\Table\DatetfsTable $Datetfs
 * @property \App\Model\Table\JobtfsTable $Jobtfs
 * @property \App\Model\Table\MemberReportsTable $MemberReports
 * @property \App\Controller\Component\CaptchaComponent $Captcha
 */
class LinksController extends FrontController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Cookie');
        $this->loadComponent('Captcha');
        $this->loadModel('Traffics');
        $this->loadModel('Datetfs');
        $this->loadModel('Jobtfs');
        $this->loadModel('MemberReports');
    }


    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->viewBuilder()->setLayout('front');
        $this->Auth->allow(['shorten', 'view', 'go', 'popad', 'restconnect','testJobtf','report','viewAlias','trafficScript','updateStep','trafficChecked','testServer', 'changeTraffic']);
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }

    public function restconnect()
    {
        $this->autoRender = false;

        $this->setResponse(
            $this->getResponse()
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('X-Robots-Tag', 'noindex, nofollow')
        );
        $this->setResponse($this->getResponse()->withType('json'));
        $ip = $this->getRequest()->getQuery('ip');
        $referrer = $this->getRequest()->getQuery('referrer');
        $src = $this->getRequest()->getQuery('src');
        $jobtf = $this->Jobtfs->find()
            ->where([
                'Jobtfs.ip' => $ip,
                'Jobtfs.status' => 0,
                'Jobtfs.created >=' => date('Y-m-d H:i:s',strtotime('-5 minutes'))
            ])
            ->first();
        if (!$jobtf) {
            $content = [
                'status' => 'error',
                'confirm_key' => 'Không thành công!'
            ];
        } else {
            $jobtf->referrer = $referrer;
            $jobtf->referrer_src = $src;
            $this->Jobtfs->save($jobtf);
            $content = [
                'status' => 'success',
                'confirm_key' => $jobtf->confirm_key
            ];
        }
        $this->setResponse($this->getResponse()->withStringBody($this->apiContent($content, 'json')));

        return $this->getResponse();
    }

    public function testJobtf(){
        $url = parse_url('https://jss77.co/');
        var_dump($url['host']);
        $jobtf = $this->Jobtfs->find()
            ->where([
                'Jobtfs.ip' => '127.0.0.1',
                'Jobtfs.status' => 0,
                'Jobtfs.created >=' => date('Y-m-d H:i:s',strtotime('-5 minutes'))
            ])
            ->first();
        var_dump($jobtf);
    }

    public function view($alias = null)
    {
        $this->setResponse(
            $this->getResponse()
                ->withHeader('X-Frame-Options', 'SAMEORIGIN')
                ->withHeader('X-Robots-Tag', 'noindex, nofollow')
        );
        if (!$alias) throw new NotFoundException(__('404 Not Found'));


        /**
         * @var \App\Model\Entity\Traffic $traffic
         * @var \App\Model\Entity\Jobtf $jobtf
         * @var \App\Model\Entity\Datetf $datetf
         */
        $this->set('alias', $alias);
        $link = $this->getLink($alias);
        if (!$link) throw new NotFoundException(__('404 Not Found'));
        $this->set('link', $link);

        if ((bool)get_option('maintenance_mode', false)) {
            return $this->redirect($link->url, 307);
        }

        $link_user_plan = get_user_plan($link->user_id);
        $this->set('link_user_plan', $link_user_plan);

        if ($link_user_plan->link_expiration && !empty($link->expiration) && $link->expiration->isPast()) {
            throw new ForbiddenException(__('The link has been expired'));
        }

        $detector = new \Detection\MobileDetect();
        if ((bool)$detector->is("Bot")) {
            if ((bool)validCrawler()) {
                return $this->redirect($link->url, 301);
            }
        }

        $plan_disable_ads = $plan_disable_captcha = $plan_onetime_captcha = $plan_direct = false;
        if ($this->Auth->user()) {
            $auth_user_plan = get_user_plan($this->Auth->user('id'));

            if ($auth_user_plan->disable_ads) {
                $plan_disable_ads = true;
            }
            if ($auth_user_plan->disable_captcha) {
                $plan_disable_captcha = true;
            }
            if ($auth_user_plan->onetime_captcha) {
                $plan_onetime_captcha = true;
            }
            if ($auth_user_plan->direct) {
                $plan_direct = true;
            }
        }

        if ($link_user_plan->visitors_remove_captcha) {
            $plan_disable_captcha = true;
        }

        $this->set('plan_disable_ads', $plan_disable_ads);

        $ad_type = $link->ad_type;
        if (!array_key_exists($ad_type, get_allowed_ads())) {
            $ad_type = get_option('member_default_advert', 1);
        }
        if ($link->user_id == 1) {
            $ad_type = get_option('anonymous_default_advert', 1);
        }

        if ($ad_type == 3) {
            $types = [1, 2];
            $ad_type = $types[array_rand($types, 1)];
        }

        $this->set('ad_type', $ad_type);

        $this->setRefererCookie($link->alias);

        // No Ads
        if ($plan_direct || $ad_type == 0) {
            $this->updateLinkHits($link);
            $this->addNormalStatisticEntry($link, $ad_type, [
                'ci' => 0,
                'cui' => 0,
                'cii' => 0,
            ], get_ip(), 10);

            return $this->redirect($link->url, 301);
        }

        $ad_captcha_above = get_option('ad_captcha_above', '');
        $ad_captcha_below = get_option('ad_captcha_below', '');
        if ($plan_disable_ads) {
            $ad_captcha_above = '';
            $ad_captcha_below = '';
        }

        $this->set('ad_captcha_above', $ad_captcha_above);
        $this->set('ad_captcha_below', $ad_captcha_below);

        $display_blog_post_shortlink = get_option('display_blog_post_shortlink', 'none');
        $post = '';

        if (in_array($display_blog_post_shortlink, ['latest', 'random'])) {
            $order = ['RAND()'];
            if ('latest' === $display_blog_post_shortlink) {
                $order = ['Posts.id' => 'DESC'];
            }

            $posts = TableRegistry::getTableLocator()->get('Posts');
            $post = $posts->find()
                ->where(['Posts.published' => 1])
                ->order($order)
                ->first();
        }
        $this->set('post', $post);

        $displayCaptchaShortlink = $this->displayCaptchaShortlink($plan_disable_captcha, $plan_onetime_captcha);
        $this->set('displayCaptchaShortlink', $displayCaptchaShortlink);

        if ($this->getRequest()->getData('action') !== 'captcha') {
            $pagesNumber = (int)\get_option('continue_pages_number', 0);
            if ($pagesNumber > 0) {
                $page = (int)$this->getRequest()->getData('page', 1);

                if ($page <= $pagesNumber) {
                    $this->set('page', $page);
                    $this->viewBuilder()->setLayout('captcha');
                    return $this->render('page');
                }
            }
        }

        $traffic_source = ($detector->isMobile()) ? 3 : 2;


        if (!$this->isProxy()){
            $created_check = Time::now()->modify('-12 minutes')->format('Y-m-d H:i:s');
            $jobtf = $this->Jobtfs->find()
                ->contain(['Traffics'])
                ->where([
                    'Jobtfs.ip' => get_ip(),
                    'Jobtfs.status' => 0,
                    'Traffics.status' => 1,
                    'Jobtfs.created >=' => $created_check
                ])
                ->first();
            if (!$jobtf) {
                $cont = ['status' => 1];
                /*$check = 0;
                $rand = rand(0, 6);
                do {
                    //phan bo
                    if ($check == 1) {
                        $cont['count_day >='] = 0;
                    } elseif ($rand == 0 && $check == 2) {
                        $cont['count_day >='] = 50;
                    } elseif ($rand == 1 || $rand == 2) {
                        $cont['count_day >='] = 200;
                    } elseif ($rand >= 3 && $rand <= 5) {
                        $cont['count_day >='] = 500;
                    } else {
                        $cont['count_day >='] = 0;
                    }
                    $check++;
                } while ($check != 3);*/

                //compare country
                $traffic = false;
                $country = get_country(get_ip());
                $cont['foreign_camp'] = ($country != 'VN') ? 1 : 0;
                $all_traffic = $this->get_traffics($cont);
                if ($country != 'VN'){
                    if ($all_traffic){
                        $new_traffics = [];
                        $jobtf = $this->Jobtfs->find()
                            ->contain(['Traffics'])
                            ->where([
                                'Jobtfs.ip' => get_ip(),
                                'Jobtfs.status' => 1,
                                'Jobtfs.created >=' => date('Y-m-d 00:00:00'),
                                'Jobtfs.created <=' => date('Y-m-d 23:59:59'),
                                'Traffics.foreign_camp' => 1
                            ])->first();
                        if (empty($jobtf)){
                            foreach ($all_traffic as $value){
                                $ip_except = json_decode($value->except_region,true);
                                $ip_only = json_decode($value->only_region,true);
                                if (!empty($ip_except) && in_array($country,$ip_except)) continue;
                                if (!empty($ip_only) && !in_array($country,$ip_only)) continue;
                                $new_traffics[] = $value;
                            }
                        }
                        $all_traffic = $new_traffics;
                        if (!$all_traffic){
                            $cont['foreign_camp'] = 0;
                            $all_traffic = $this->get_traffics($cont);
                        }
                    } else {
                        $cont['foreign_camp'] = 0;
                        $all_traffic = $this->get_traffics($cont);
                    }
                }

                if ($all_traffic){
                    $traffic = $this->run_traffic_view($all_traffic);

                    if (!$traffic) {
                        throw new NotFoundException(__('404 Not Found'));
                        // return $this->redirect($link->url, 301);
                    } else {
                        $traffic2_url = $traffic->traffic_ver2_url;
                        if (!empty($traffic2_url)){
                            $traffic2_url = json_decode($traffic2_url);
                            if (is_array($traffic2_url)){
                                $traffic2_url = $traffic2_url[rand(array_key_first($traffic2_url),array_key_last($traffic2_url))];
                            }
                        }

                        $jobtf = $this->Jobtfs->newEntity();
                        $jobtf = $this->Jobtfs->patchEntity($jobtf, array(
                            'ip' => get_ip(),
                            'traffic_id' => $traffic->id,
                            'user_id' => $link->user_id,
                            'status' => 0,
                            'device' => $traffic_source == 3 ? 0 : 1,
                            'confirm_key' => $this->generateRandomString(),
                            'link_url' => $traffic->url,
                            'traffic2_url' => $traffic2_url
                        ));
                        $this->Jobtfs->save($jobtf);
                    }
                } else {
                    throw new NotFoundException(__('404 Not Found'));
                    // return $this->redirect($link->url, 301);
                }
            } else {
                $traffic = $this->Traffics->find()
                    ->contain(['Datetfs'])
                    ->where([
                        'Traffics.status' => 1,
                        'Traffics.id' => $jobtf->traffic_id,
                    ])
                    ->order(['RAND()'])
                    ->first();
            }

            $hasReport = false;
            if ($traffic){
                $hasReport = $this->MemberReports->find()->where([
                    'ip' => get_ip(),
                    'traffic_id' => $traffic->id,
                    'status' => 0,
                    'date >=' => date('Y-m-d')
                ])->count();
            }

            if (($this->getRequest()->is(['post','put']))) {
                $code_ = $this->getRequest()->getData('code_');
                $jobtf = $this->Jobtfs->find()
                    ->where([
                        'Jobtfs.confirm_key' => $code_,
                        'Jobtfs.status' => 0,
                        'Jobtfs.ip' => get_ip()
                    ])
                    ->first();
                if (!$jobtf) {
                    $this->Flash->error(__('Mã code bạn vừa nhập không hợp lệ, hãy thử lại nhé!'));
                } else {
                    $date_check = Time::now()->modify('-12 minutes')->format('Y-m-d H:i:s');
                    $jobtf_date = $jobtf->created->format('Y-m-d H:i:s');
                    if ($jobtf_date < $date_check){
                        $this->Flash->error('Code của bạn đã hết hạn vui lòng thử lại');
                    } else {
                        $datetf = $this->Datetfs->find()
                            ->where([
                                'Datetfs.traffic_id' => $jobtf->traffic_id,
                                'Datetfs.date' => date('Y-m-d')
                            ])
                            ->first();

                        $datetf = $this->Datetfs->patchEntity($datetf, array('views' => $datetf->views + 1));
                        $this->Datetfs->save($datetf);

                        $traffic = $this->Traffics->find()
                            ->where([
                                'Traffics.id' => $jobtf->traffic_id
                            ])
                            ->first();
                        $traffic = $this->Traffics->patchEntity($traffic, [
                            'views' => $traffic->views + 1,
                            'view_day' => $traffic->view_day + 1
                        ]);
                        $this->Traffics->save($traffic);

                        $jobtf = $this->Jobtfs->patchEntity($jobtf, array(
                            'status' => 1
                        ));
                        $this->Jobtfs->save($jobtf);

                        $country = $this->Links->Statistics->get_country(get_ip());
                        $paidAds = (object)$this->getPaidAds($ad_type, $traffic_source, $country,$traffic);
                        $ad_form_data = [
                            'mode' => $paidAds->mode,
                            'alias' => $link->alias,
                            'ci' => $paidAds->ci,
                            'cui' => $paidAds->cui,
                            'cii' => $paidAds->cii,
                            'country' => $country,
                            'advertiser_price' => $paidAds->advertiser_price,
                            'publisher_price' => $paidAds->publisher_price,
                            'ad_type' => $ad_type,
                            'timer' => $link_user_plan->timer ?? 5,
                            't' => time(),
                        ];

                        $content = $this->calcEarnings($ad_form_data, $link, $ad_form_data['ad_type'], $traffic->kind);

                        return $this->redirect($link->url, 301);
                    }
                }
            }
            $this->set('traffic', $traffic);
            $this->set('hasReport',$hasReport);
        } else {
            $this->set('isProxy',1);
        }
        $this->viewBuilder()->setLayout('captcha');
        $this->render('traffic');
    }

    public function run_traffic_view($all_traffic,$type = 'beta'){
        if ($type == 'beta'){
            return $this->traffic_allotment($all_traffic);
        } else {
            return $this->traffic_origin($all_traffic);
        }
    }

    public function traffic_allotment($all_traffic){
        if (!empty($all_traffic)){
            // Recreate another array where we have multiple occurence of the same value (nb_of_occurence = priority)
            $list = [];
            foreach ($all_traffic as $traffic) {
                $jobtf = $this->Jobtfs->find()
                    ->where([
                        'Jobtfs.ip' => get_ip(),
                        'Jobtfs.status' => 1,
                        'Jobtfs.traffic_id' => $traffic->id
                    ])
                    ->first();

                $datetf = $this->Datetfs->find()
                    ->where([
                        'Datetfs.traffic_id' => $traffic->id,
                        'Datetfs.date' => date('Y-m-d')
                    ])
                    ->first();

                if (!$datetf) {
                    $add = $this->Datetfs->newEntity();
                    $add = $this->Datetfs->patchEntity($add,
                        [
                            'date' => date('Y-m-d'),
                            'views' => 0,
                            'user_id' => (!empty($traffic->user_id)) ? $traffic->user_id : '',
                            'traffic_id' => $traffic->id
                        ]
                    );
                    if ($this->Datetfs->save($add)){
                        if ($jobtf) continue;
                    }
                } else {
                    if ($datetf->views < $traffic->count_day) {
                        if ($jobtf) continue;
                    }
                }

                $nbOfOccurence = $traffic->count_day;

                for($i = 0 ; $i < $nbOfOccurence ; $i++) {
                    $list[] = $traffic;
                }
            }

            // Count the number of elements in this new array
            $nbOfElement = count($list);

            // Generate a random index between 0 and (number of element - 1)
            $randomIndex = rand(0,($nbOfElement - 1));

            // Retrive the random value
            return (!empty($list) && is_array($list) && count($list)) ? $list[$randomIndex] : false;
        }
    }

    public function traffic_origin($all_traffic){
        $traffic = [];
        foreach ($all_traffic as $value) {
            $jobtf = $this->Jobtfs->find()
                ->where([
                    'Jobtfs.ip' => get_ip(),
                    'Jobtfs.status' => 1,
                    'Jobtfs.traffic_id' => $value->id,
                ])
                ->first();
            $random = 1;
            $datetf = $this->Datetfs->find()
                ->where([
                    'Datetfs.traffic_id' => $value->id,
                    'Datetfs.date' => date('Y-m-d')
                ])
                ->first();
            if (!$datetf) {
                $add = $this->Datetfs->newEntity();
                $add = $this->Datetfs->patchEntity($add,
                    array(
                        'date' => date('Y-m-d'),
                        'views' => 0,
                        'user_id' => (!empty($value->user_id)) ? $value->user_id : '',
                        'traffic_id' => $value->id
                    )
                );
                if ($this->Datetfs->save($add)) {
                    if (!$jobtf && $random == 1) {
                        $traffic = $value;
                        break;
                    }
                }

            } else {
                if ($datetf->views < $value->count_day) {
                    if (!$jobtf && $random == 1) {
                        $traffic = $value;
                        break;
                    }
                }
            }

        }
        return $traffic;
    }

    public function get_traffics($cont){
        $device = new MobileDetect();

        $traffics = $this->Traffics->find()
            ->contain(['Datetfs'])
            ->where($cont)
            ->where('Traffics.views <= Traffics.count')
            ->where('Traffics.count_day > Traffics.view_day');

        if ($device->isMobile()){
            $traffics->where(['Traffics.device IN' => [0,2]]);
        } else {
            $traffics->where(['Traffics.device IN' => [0,1]]);
        }

        $traffics = $traffics->order(['RAND()'])
            ->limit(3)
            ->toArray();

        return $traffics;
    }

    public function changeTraffic($alias = null)
    {
        $created_check = Time::now()->modify('-12 minutes')->format('Y-m-d H:i:s');
        $jobtf = $this->Jobtfs->find()
            ->contain(['Traffics'])
            ->where([
                'Jobtfs.ip' => get_ip(),
                'Jobtfs.status' => 0,
                'Traffics.status' => 1,
                'Jobtfs.created >=' => $created_check
            ])
            ->first();
        if ($jobtf) {
            $cont = ['status' => 1];
            $traffic = false;
            $country = get_country(get_ip());
            $cont['foreign_camp'] = ($country != 'VN') ? 1 : 0;
            $all_traffic = $this->get_traffics($cont);
            $new_traffics = [];
            foreach ($all_traffic as $value){
                $id = $value -> id;
                // Skip if the traffic is match to current traffic
                if ($jobtf->traffic_id == $id) {
                        continue;
                }
                $new_traffics[] = $value;
            }
            if (count($new_traffics) > 0 ) {
                // Random new one
                $traffic = $new_traffics[array_rand($new_traffics, 1)];
                $jobtf = $this->Jobtfs->patchEntity($jobtf, array(
                    'ip' => get_ip(),
                    'traffic_id' => $traffic->id,
                    'user_id' => $link->user_id,
                    'status' => 0,
                    'device' => $traffic_source == 3 ? 0 : 1,
                    'confirm_key' => $this->generateRandomString(),
                    'link_url' => $traffic->url,
                    'traffic2_url' => $traffic2_url
                ));
                $this->Jobtfs->save($jobtf);
            }
        }

        return $this->redirect(['action' => 'view', 'alias' => $alias]);

    }

    public function viewAlias($alias = null)
    {
        $this->setResponse(
            $this->getResponse()
                ->withHeader('X-Frame-Options', 'SAMEORIGIN')
                ->withHeader('X-Robots-Tag', 'noindex, nofollow')
        );
        if (!$alias) throw new NotFoundException(__('404 Not Found'));


        /**
         * @var \App\Model\Entity\Traffic $traffic
         * @var \App\Model\Entity\Jobtf $jobtf
         * @var \App\Model\Entity\Datetf $datetf
         */
        $link = $this->getLink($alias);
        if (!$link) throw new NotFoundException(__('404 Not Found'));
        $this->set('link', $link);

        if ((bool)get_option('maintenance_mode', false)) {
            return $this->redirect($link->url, 307);
        }

        $link_user_plan = get_user_plan($link->user_id);
        $this->set('link_user_plan', $link_user_plan);

        if ($link_user_plan->link_expiration && !empty($link->expiration) && $link->expiration->isPast()) {
            throw new ForbiddenException(__('The link has been expired'));
        }

        $detector = new \Detection\MobileDetect();
        if ((bool)$detector->is("Bot")) {
            if ((bool)validCrawler()) {
                return $this->redirect($link->url, 301);
            }
        }

        $plan_disable_ads = $plan_disable_captcha = $plan_onetime_captcha = $plan_direct = false;
        if ($this->Auth->user()) {
            $auth_user_plan = get_user_plan($this->Auth->user('id'));

            if ($auth_user_plan->disable_ads) {
                $plan_disable_ads = true;
            }
            if ($auth_user_plan->disable_captcha) {
                $plan_disable_captcha = true;
            }
            if ($auth_user_plan->onetime_captcha) {
                $plan_onetime_captcha = true;
            }
            if ($auth_user_plan->direct) {
                $plan_direct = true;
            }
        }

        if ($link_user_plan->visitors_remove_captcha) {
            $plan_disable_captcha = true;
        }

        $this->set('plan_disable_ads', $plan_disable_ads);

        $ad_type = $link->ad_type;
        if (!array_key_exists($ad_type, get_allowed_ads())) {
            $ad_type = get_option('member_default_advert', 1);
        }
        if ($link->user_id == 1) {
            $ad_type = get_option('anonymous_default_advert', 1);
        }

        if ($ad_type == 3) {
            $types = [1, 2];
            $ad_type = $types[array_rand($types, 1)];
        }

        $this->set('ad_type', $ad_type);

        $this->setRefererCookie($link->alias);

        // No Ads
        if ($plan_direct || $ad_type == 0) {
            $this->updateLinkHits($link);
            $this->addNormalStatisticEntry($link, $ad_type, [
                'ci' => 0,
                'cui' => 0,
                'cii' => 0,
            ], get_ip(), 10);

            return $this->redirect($link->url, 301);
        }

        $ad_captcha_above = get_option('ad_captcha_above', '');
        $ad_captcha_below = get_option('ad_captcha_below', '');
        if ($plan_disable_ads) {
            $ad_captcha_above = '';
            $ad_captcha_below = '';
        }

        $this->set('ad_captcha_above', $ad_captcha_above);
        $this->set('ad_captcha_below', $ad_captcha_below);

        $display_blog_post_shortlink = get_option('display_blog_post_shortlink', 'none');
        $post = '';

        if (in_array($display_blog_post_shortlink, ['latest', 'random'])) {
            $order = ['RAND()'];
            if ('latest' === $display_blog_post_shortlink) {
                $order = ['Posts.id' => 'DESC'];
            }

            $posts = TableRegistry::getTableLocator()->get('Posts');
            $post = $posts->find()
                ->where(['Posts.published' => 1])
                ->order($order)
                ->first();
        }
        $this->set('post', $post);

        $displayCaptchaShortlink = $this->displayCaptchaShortlink($plan_disable_captcha, $plan_onetime_captcha);
        $this->set('displayCaptchaShortlink', $displayCaptchaShortlink);

        if ($this->getRequest()->getData('action') !== 'captcha') {
            $pagesNumber = (int)\get_option('continue_pages_number', 0);
            if ($pagesNumber > 0) {
                $page = (int)$this->getRequest()->getData('page', 1);

                if ($page <= $pagesNumber) {
                    $this->set('page', $page);
                    $this->viewBuilder()->setLayout('captcha');
                    return $this->render('page');
                }
            }
        }

        $traffic_source = ($detector->isMobile()) ? 3 : 2;


        if (!$this->isProxy()){
            $created_check = Time::now()->modify('-12 minutes')->format('Y-m-d H:i:s');
            $jobtf = $this->Jobtfs->find()
                ->contain(['Traffics'])
                ->where([
                    'Jobtfs.ip' => get_ip(),
                    'Jobtfs.status' => 0,
                    'Traffics.status' => 1,
                    'Jobtfs.created >=' => $created_check
                ])
                ->first();
            if (!$jobtf) {
                $traffic = false;
                $all_traffic = $this->Traffics->find()
                    ->contain(['Datetfs'])
                    ->where(['traffic_ver2' => 1])
                    ->order(['RAND()'])
                    ->limit(3)
                    ->toArray();

                if ($all_traffic){
                    $traffic = $all_traffic[rand(0,count($all_traffic) - 1)];
                    if (!$traffic) {
                        return $this->redirect($link->url, 301);
                    } else {
                        $traffic2_url = $traffic->traffic_ver2_url;
                        if (!empty($traffic2_url)){
                            $traffic2_url = json_decode($traffic2_url);
                            if (is_array($traffic2_url)){
                                $traffic2_url = $traffic2_url[rand(array_key_first($traffic2_url),array_key_last($traffic2_url))];
                            }
                        }

                        $jobtf = $this->Jobtfs->newEntity();
                        $jobtf = $this->Jobtfs->patchEntity($jobtf, array(
                            'ip' => get_ip(),
                            'traffic_id' => $traffic->id,
                            'user_id' => $link->user_id,
                            'status' => 0,
                            'device' => $traffic_source == 3 ? 0 : 1,
                            'confirm_key' => $this->generateRandomString(),
                            'link_url' => $traffic->url,
                            'traffic2_url' => $traffic2_url
                        ));
                        $this->Jobtfs->save($jobtf);
                    }
                } else {
                    return $this->redirect($link->url, 301);
                }
            } else {
                $traffic = $this->Traffics->find()
                    ->contain(['Datetfs'])
                    ->where([
                        'Traffics.status' => 1,
                        'Traffics.id' => $jobtf->traffic_id,
                    ])
                    ->order(['RAND()'])
                    ->first();
            }

            if (($this->getRequest()->is(['post','put']))) {
                $code_ = $this->getRequest()->getData('code_');
                $jobtf = $this->Jobtfs->find()
                    ->where([
                        'Jobtfs.confirm_key' => $code_,
                        'Jobtfs.status' => 0,
                        'Jobtfs.ip' => get_ip()
                    ])
                    ->first();
                if (!$jobtf) {
                    $this->Flash->error(__('Mã code bạn vừa nhập không hợp lệ, hãy thử lại nhé!'));
                } else {
                    $date_check = Time::now()->modify('-12 minutes')->format('Y-m-d H:i:s');
                    $jobtf_date = $jobtf->created->format('Y-m-d H:i:s');
                    if ($jobtf_date < $date_check){
                        $this->Flash->error('Code của bạn đã hết hạn vui lòng thử lại');
                    } else {
                        return $this->redirect($link->url, 301);
                    }
                }
            }
            $this->set('traffic', $traffic);
        } else {
            $this->set('isProxy',1);
        }
        $this->viewBuilder()->setLayout('captcha');
        $this->render('traffic');
    }

    public function trafficChecked($alias = null, $id = null){
//        Configure::write('debug', 2);
        $ip_list = $this->Jobtfs->find()->where(['ip' => get_ip()])->toArray();
        if (!empty($ip_list)){
            foreach ($ip_list as $ip){
                $this->Jobtfs->delete($ip);
            }
        }
        $user = $this->Auth->user();
        if ($user['role'] != 'admin') return false;

        $this->setResponse(
            $this->getResponse()
                ->withHeader('X-Frame-Options', 'SAMEORIGIN')
                ->withHeader('X-Robots-Tag', 'noindex, nofollow')
        );
        if (!$alias) throw new NotFoundException(__('404 Not Found'));


        /**
         * @var \App\Model\Entity\Traffic $traffic
         * @var \App\Model\Entity\Jobtf $jobtf
         * @var \App\Model\Entity\Datetf $datetf
         */
        $link = $this->getLink($alias);
        if (!$link) throw new NotFoundException(__('404 Not Found'));
        $this->set('link', $link);

        if ((bool)get_option('maintenance_mode', false)) {
            return $this->redirect($link->url, 307);
        }

        $link_user_plan = get_user_plan($link->user_id);
        $this->set('link_user_plan', $link_user_plan);

        if ($link_user_plan->link_expiration && !empty($link->expiration) && $link->expiration->isPast()) {
            throw new ForbiddenException(__('The link has been expired'));
        }

        $detector = new \Detection\MobileDetect();
        if ((bool)$detector->is("Bot")) {
            if ((bool)validCrawler()) {
                return $this->redirect($link->url, 301);
            }
        }

        $plan_disable_ads = $plan_disable_captcha = $plan_onetime_captcha = $plan_direct = false;
        if ($this->Auth->user()) {
            $auth_user_plan = get_user_plan($this->Auth->user('id'));

            if ($auth_user_plan->disable_ads) {
                $plan_disable_ads = true;
            }
            if ($auth_user_plan->disable_captcha) {
                $plan_disable_captcha = true;
            }
            if ($auth_user_plan->onetime_captcha) {
                $plan_onetime_captcha = true;
            }
            if ($auth_user_plan->direct) {
                $plan_direct = true;
            }
        }

        if ($link_user_plan->visitors_remove_captcha) {
            $plan_disable_captcha = true;
        }

        $this->set('plan_disable_ads', $plan_disable_ads);

        $ad_type = $link->ad_type;
        if (!array_key_exists($ad_type, get_allowed_ads())) {
            $ad_type = get_option('member_default_advert', 1);
        }
        if ($link->user_id == 1) {
            $ad_type = get_option('anonymous_default_advert', 1);
        }

        if ($ad_type == 3) {
            $types = [1, 2];
            $ad_type = $types[array_rand($types, 1)];
        }

        $this->set('ad_type', $ad_type);

        $this->setRefererCookie($link->alias);

        // No Ads
        if ($plan_direct || $ad_type == 0) {
            $this->updateLinkHits($link);
            $this->addNormalStatisticEntry($link, $ad_type, [
                'ci' => 0,
                'cui' => 0,
                'cii' => 0,
            ], get_ip(), 10);

            return $this->redirect($link->url, 301);
        }

        $ad_captcha_above = get_option('ad_captcha_above', '');
        $ad_captcha_below = get_option('ad_captcha_below', '');
        if ($plan_disable_ads) {
            $ad_captcha_above = '';
            $ad_captcha_below = '';
        }

        $this->set('ad_captcha_above', $ad_captcha_above);
        $this->set('ad_captcha_below', $ad_captcha_below);

        $display_blog_post_shortlink = get_option('display_blog_post_shortlink', 'none');
        $post = '';

        if (in_array($display_blog_post_shortlink, ['latest', 'random'])) {
            $order = ['RAND()'];
            if ('latest' === $display_blog_post_shortlink) {
                $order = ['Posts.id' => 'DESC'];
            }

            $posts = TableRegistry::getTableLocator()->get('Posts');
            $post = $posts->find()
                ->where(['Posts.published' => 1])
                ->order($order)
                ->first();
        }
        $this->set('post', $post);

        $displayCaptchaShortlink = $this->displayCaptchaShortlink($plan_disable_captcha, $plan_onetime_captcha);
        $this->set('displayCaptchaShortlink', $displayCaptchaShortlink);

        if ($this->getRequest()->getData('action') !== 'captcha') {
            $pagesNumber = (int)\get_option('continue_pages_number', 0);
            if ($pagesNumber > 0) {
                $page = (int)$this->getRequest()->getData('page', 1);

                if ($page <= $pagesNumber) {
                    $this->set('page', $page);
                    $this->viewBuilder()->setLayout('captcha');
                    return $this->render('page');
                }
            }
        }

        $traffic_source = ($detector->isMobile()) ? 3 : 2;

        $traffic = $this->Traffics->find()
            ->contain(['Datetfs'])
            ->where(['id' => $id])
            ->first();

        if (!$traffic) {
            return $this->redirect($link->url, 301);
        } else {
            $traffic2_url = $traffic->traffic_ver2_url;
            if (!empty($traffic2_url)){
                $traffic2_url = json_decode($traffic2_url);
                if (is_array($traffic2_url)){
                    $traffic2_url = $traffic2_url[rand(array_key_first($traffic2_url),array_key_last($traffic2_url))];
                }
            }

            $jobtf = $this->Jobtfs->newEntity();
            $jobtf = $this->Jobtfs->patchEntity($jobtf, array(
                'ip' => get_ip(),
                'traffic_id' => $traffic->id,
                'user_id' => $link->user_id,
                'status' => 0,
                'device' => $traffic_source == 3 ? 0 : 1,
                'confirm_key' => $this->generateRandomString(),
                'link_url' => $traffic->url,
                'traffic2_url' => $traffic2_url
            ));
            $this->Jobtfs->save($jobtf);
        }

        if (($this->getRequest()->is(['post','put']))) {
            $code_ = $this->getRequest()->getData('code_');
            $jobtf = $this->Jobtfs->find()
                ->where([
                    'Jobtfs.confirm_key' => $code_,
                    'Jobtfs.status' => 0,
                    'Jobtfs.ip' => get_ip()
                ])
                ->first();
            if (!$jobtf) {
                $this->Flash->error(__('Mã code bạn vừa nhập không hợp lệ, hãy thử lại nhé!'));
            } else {
                $date_check = Time::now()->modify('-12 minutes')->format('Y-m-d H:i:s');
                $jobtf_date = $jobtf->created->format('Y-m-d H:i:s');
                if ($jobtf_date < $date_check){
                    $this->Flash->error('Code của bạn đã hết hạn vui lòng thử lại');
                } else {
                    return $this->redirect($link->url, 301);
                }
            }
        }

        $this->set('traffic', $traffic);
        $this->viewBuilder()->setLayout('captcha');
        $this->render('traffic');
    }

    public function popad()
    {
        $this->autoRender = false;

        if ($this->getRequest()->is('post')) {
            $pop_ad_data = data_decrypt($this->getRequest()->getData('pop_ad'));

            $this->calcEarnings($pop_ad_data, $pop_ad_data['link'], 3);

            return $this->redirect($pop_ad_data['website_url'], 301);
        }
    }

    public function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function go()
    {
        $this->autoRender = false;
        $this->setResponse($this->getResponse()->withType('json'));

        $ad_form_data = data_decrypt($this->getRequest()->getData('ad_form_data'));

        $t = (int)$ad_form_data['t'];
        $diff_seconds = (int)(time() - $t);
        $counter_value = (int)$ad_form_data['timer'];

        if ($diff_seconds < $counter_value) {
            $content = [
                'status' => 'error',
                'message' => 'Bad Request.',
                'url' => '',
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->getResponse();
        }

        if (!$this->getRequest()->is('ajax')) {
            $content = [
                'status' => 'error',
                'message' => 'Bad Request.',
                'url' => '',
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->getResponse();
        }

        /**
         * @var \App\Model\Entity\Link $link
         */
        $link = $this->Links->find()
            //->contain(['Users'])
            ->contain([
                'Users' => [
                    'fields' => ['id', 'username', 'status', 'disable_earnings'],
                ],
            ])
            ->where([
                'Links.alias' => $ad_form_data['alias'],
                'Links.status <>' => 3,
            ])
            ->first();
        if (!$link) {
            $content = [
                'status' => 'error',
                'message' => '404 Not Found.',
                'url' => '',
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->getResponse();
        }

        $content = $this->calcEarnings($ad_form_data, $link, $ad_form_data['ad_type']);

        //$content['url'] = $content['url'];

        $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

        return $this->getResponse();
    }

    protected function setRefererCookie($alias)
    {
        if (isset($_COOKIE['ref' . $alias])) {
            return;
        }

        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

        \setcookie('ref' . $alias, \data_encrypt($referer), \time() + 5 * 60, '/', '', false, true);
    }

    protected function getRefererCookie($alias)
    {
        $referer_url = '';
        if (isset($_COOKIE['ref' . $alias])) {
            $referer_url = \data_decrypt($_COOKIE['ref' . $alias]);
        }

        return $referer_url;
    }

    protected function getPaidAds($ad_type, $traffic_source, $country,$traffic = '')
    {
        $paidAds = new \stdClass();

        if (get_option('earning_mode', 'campaign') === 'simple') {
            $prices = [];
            if ($ad_type === 1) {
                $prices = get_option('payout_rates_interstitial', []);
                $paidAds->website_url = get_option('interstitial_ad_url', '');
            }

            if ($ad_type === 2) {
                $prices = get_option('payout_rates_banner', []);
                if (!empty($traffic) && $traffic->traffic_ver2 == 1) $prices = get_option('payout_rates_banner_traffics2', []);
                $paidAds->banner_size = '';
                $paidAds->banner_code = '';
            }

            if ($ad_type === 3) {
                $prices = get_option('payout_rates_popup', []);
                $paidAds->website_url = get_option('popup_ad_url', '');
            }

            $publisher_price = 0;
            if (!empty($prices[$country][$traffic_source])) {
                $publisher_price = $prices[$country][$traffic_source];
            } elseif (!empty($prices['all'][$traffic_source])) {
                $publisher_price = $prices['all'][$traffic_source];
            }

            $paidAds->mode = 'simple';
            $paidAds->advertiser_price = 0;
            $paidAds->publisher_price = $publisher_price;
            $paidAds->ci = 0;
            $paidAds->cui = 0;
            $paidAds->cii = 0;

            return $paidAds;
        }

        $CampaignItems = TableRegistry::getTableLocator()->get('CampaignItems');

        $campaign_items = $CampaignItems->find()
            ->contain(['Campaigns'])
            ->where([
                'Campaigns.default_campaign' => 0,
                'Campaigns.ad_type' => $ad_type,
                'Campaigns.status' => 1,
                "Campaigns.traffic_source IN (1, :traffic_source)",
                'CampaignItems.weight <' => 100,
                'CampaignItems.country' => $country,
            ])
            ->order(['CampaignItems.weight' => 'ASC'])
            ->bind(':traffic_source', $traffic_source, 'integer')
            ->limit(10)
            ->toArray();

        if (count($campaign_items) == 0) {
            $campaign_items = $CampaignItems->find()
                ->contain(['Campaigns'])
                ->where([
                    'Campaigns.default_campaign' => 0,
                    'Campaigns.ad_type' => $ad_type,
                    'Campaigns.status' => 1,
                    "Campaigns.traffic_source IN (1, :traffic_source)",
                    'CampaignItems.weight <' => 100,
                    'CampaignItems.country' => 'all',
                ])
                ->order(['CampaignItems.weight' => 'ASC'])
                ->bind(':traffic_source', $traffic_source, 'integer')
                ->limit(10)
                ->toArray();
        }

        if (count($campaign_items) == 0) {
            $campaign_items = $CampaignItems->find()
                ->contain(['Campaigns'])
                ->where([
                    'Campaigns.default_campaign' => 1,
                    'Campaigns.ad_type' => $ad_type,
                    'Campaigns.status' => 1,
                    "Campaigns.traffic_source IN (1, :traffic_source)",
                    'CampaignItems.weight <' => 100,
                    "CampaignItems.country IN ( 'all', :country)",
                ])
                ->order(['CampaignItems.weight' => 'ASC'])
                ->bind(':traffic_source', $traffic_source, 'integer')
                ->bind(':country', $country, 'string')
                ->limit(10)
                ->toArray();
        }

        shuffle($campaign_items);

        $campaign_item = array_values($campaign_items)[0];

        $paidAds->mode = 'campaign';
        $paidAds->advertiser_price = $campaign_item->advertiser_price;
        $paidAds->publisher_price = $campaign_item->publisher_price;
        $paidAds->ci = $campaign_item->campaign_id;
        $paidAds->cui = $campaign_item->campaign->user_id;
        $paidAds->cii = $campaign_item->id;
        $paidAds->website_url = $campaign_item->campaign->website_url;
        $paidAds->banner_size = $campaign_item->campaign->banner_size;
        $paidAds->banner_code = $campaign_item->campaign->banner_code;

        return $paidAds;
    }

    /**
     * @param array|object $data
     * @param \App\Model\Entity\Link $link
     * @param int $ad_type
     * @return array
     */
    protected function calcEarnings($data, $link, $ad_type, $kind='google')
    {
        /**
         * Views reasons
         * 1- Earn
         * 2- Disabled cookie
         * 3- Anonymous user
         * 4- Adblock
         * 5- Proxy
         * 6- IP changed
         * 7- Not unique
         * 8- Full weight
         * 9- Default campaign
         * 10- Direct
         * 11- Invalid Country
         * 12- Earnings disabled
         * 13- User disabled earnings
         * 14- Earn but kind='direct'
         */

        $referer_url = $this->getRefererCookie($link->alias);

        /**
         * Check if user disabled earnings
         */
        if ($link->user->disable_earnings) {
            // Update link hits
            $this->updateLinkHits($link);
            $this->addNormalStatisticEntry($link, $ad_type, $data, get_ip(), 13);
            $content = [
                'status' => 'success',
                //'message' => 'Go without Earn because User disabled earnings',
                'message' => '',
                'url' => $link->url,
            ];

            return $content;
        }

        /**
         * Check if earnings are disabled
         */
        if (!(bool)get_option('enable_publisher_earnings', 1)) {
            // Update link hits
            $this->updateLinkHits($link);
            $this->addNormalStatisticEntry($link, $ad_type, $data, get_ip(), 12);
            $content = [
                'status' => 'success',
                //'message' => 'Go without Earn because earnings are disabled',
                'message' => '',
                'url' => $link->url,
            ];

            return $content;
        }

        /**
         * Check if valid country
         */
        if ($data['country'] == 'Others') {
            // Update link hits
            $this->updateLinkHits($link);
            $this->addNormalStatisticEntry($link, $ad_type, $data, get_ip(), 11);
            $content = [
                'status' => 'success',
                //'message' => 'Go without Earn because invalid country',
                'message' => '',
                'url' => $link->url,
            ];

            return $content;
        }

        /**
         * Check if cookie valid
         */
        $cookie['ip'] = get_ip();
        // if (!is_array($cookie)) {
        //     // Update link hits
        //     $this->updateLinkHits($link);
        //     $this->addNormalStatisticEntry($link, $ad_type, $data, get_ip(), 2);
        //     $content = [
        //         'status' => 'success',
        //         //'message' => 'Go without Earn because no cookie',
        //         'message' => '',
        //         'url' => $link->url,
        //     ];

        //     return $content;
        // }

        /**
         * Check if anonymous user
         */
        if ('anonymous' == $link->user->username) {
            // Update link hits
            $this->updateLinkHits($link);
            $this->addNormalStatisticEntry($link, $ad_type, $data, $cookie['ip'], 3);
            $content = [
                'status' => 'success',
                //'message' => 'Go without Earn because anonymous user',
                'message' => '',
                'url' => $link->url,
            ];

            return $content;
        }

        /**
         * Check if IP changed
         */
        if ($cookie['ip'] != get_ip()) {
            // Update link hits
            $this->updateLinkHits($link);
            $this->addNormalStatisticEntry($link, $ad_type, $data, $cookie['ip'], 6);
            $content = [
                'status' => 'success',
                //'message' => 'Go without Earn because IP changed',
                'message' => '',
                'url' => $link->url,
            ];

            return $content;
        }

        /**
         * Check for Adblock
         */
        // if (!isset($_COOKIE['ab']) || in_array($_COOKIE['ab'], [0, 1])) {
        //     // Update link hits
        //     $this->updateLinkHits($link);
        //     $this->addNormalStatisticEntry($link, $ad_type, $data, $cookie['ip'], 4);
        //     $content = [
        //         'status' => 'success',
        //         //'message' => 'Go without Earn because Adblock',
        //         'message' => '',
        //         'url' => $link->url,
        //     ];

        //     return $content;
        // }

        /**
         * Check if blocked referer domain
         */
        if ($this->isRefererBlocked($referer_url)) {
            // Update link hits
            $this->updateLinkHits($link);
            $this->addNormalStatisticEntry($link, $ad_type, $data, get_ip(), 14);
            $content = [
                'status' => 'success',
                //'message' => 'Go without Earn because blocked referer',
                'message' => '',
                'url' => $link->url,
            ];

            return $content;
        }


        // Campaign mode checks
        if ($data['mode'] === 'campaign') {
            /**
             * Check Campaign Item weight
             */
            $CampaignItems = TableRegistry::getTableLocator()->get('CampaignItems');

            $campaign_item = $CampaignItems->find()
                ->contain(['Campaigns'])
                ->where(['CampaignItems.id' => $data['cii']])
                ->where(['CampaignItems.weight <' => 100])
                ->where(['Campaigns.status' => 1])
                ->first();

            if (!$campaign_item) {
                // Update link hits
                $this->updateLinkHits($link);
                $this->addNormalStatisticEntry($link, $ad_type, $data, $cookie['ip'], 8);
                $content = [
                    'status' => 'success',
                    //'message' => 'Go without Earn because Campaign Item weight is full.',
                    'message' => '',
                    'url' => $link->url,
                ];

                return $content;
            }

            /**
             * Check if default campaign
             */
            if ($campaign_item->campaign->default_campaign) {
                // Update link hits
                $this->updateLinkHits($link);
                $this->addNormalStatisticEntry($link, $ad_type, $data, $cookie['ip'], 9);
                $content = [
                    'status' => 'success',
                    //'message' => 'Go without Earn because Default Campaign.',
                    'message' => '',
                    'url' => $link->url,
                ];

                return $content;
            }
        }

        /**
         * Check if proxy
         */
        if ($this->isProxy()) {
            // Update link hits
            $this->updateLinkHits($link);
            $this->addNormalStatisticEntry($link, $ad_type, $data, get_ip(), 5);
            $content = [
                'status' => 'success',
                //'message' => 'Go without Earn because proxy',
                'message' => '',
                'url' => $link->url,
            ];

            return $content;
        }

        $link_user_plan = get_user_plan($link->user_id);

        $views_hourly_limit = (int)$link_user_plan->views_hourly_limit;

        if ($views_hourly_limit > 0) {
            $hour = Time::now()->hour;
            $startOfHour = Time::now()->setTimeFromTimeString($hour . ':00:00')->format('Y-m-d H:i:s');
            $endOfHour = Time::now()->setTimeFromTimeString($hour . ':59:59')->format('Y-m-d H:i:s');

            $hourly_count = $this->Links->Statistics->find()
                ->where([
                    'Statistics.user_id' => $link->user_id,
                    'Statistics.reason' => 1,
                    'Statistics.created BETWEEN :startOfHour AND :endOfHour',
                ])
                ->bind(':startOfHour', $startOfHour, 'datetime')
                ->bind(':endOfHour', $endOfHour, 'datetime')
                ->count();

            if ($hourly_count >= $views_hourly_limit) {
                // Update link hits
                $this->updateLinkHits($link);
                $this->addNormalStatisticEntry($link, $ad_type, $data, $cookie['ip'], 15);
                $content = [
                    'status' => 'success',
                    //'message' => 'Go without Earn, reached the hourly limit',
                    'message' => '',
                    'url' => $link->url,
                ];

                return $content;
            }
        }

        $views_daily_limit = (int)$link_user_plan->views_daily_limit;

        if ($views_daily_limit > 0) {
            $startOfDay = Time::now()->startOfDay()->format('Y-m-d H:i:s');
            $endOfDay = Time::now()->endOfDay()->format('Y-m-d H:i:s');

            $daily_count = $this->Links->Statistics->find()
                ->where([
                    'Statistics.user_id' => $link->user_id,
                    'Statistics.reason' => 1,
                    'Statistics.created BETWEEN :startOfDay AND :endOfDay',
                ])
                ->bind(':startOfDay', $startOfDay, 'datetime')
                ->bind(':endOfDay', $endOfDay, 'datetime')
                ->count();

            if ($daily_count >= $views_daily_limit) {
                // Update link hits
                $this->updateLinkHits($link);
                $this->addNormalStatisticEntry($link, $ad_type, $data, $cookie['ip'], 16);
                $content = [
                    'status' => 'success',
                    //'message' => 'Go without Earn, reached the daily limit',
                    'message' => '',
                    'url' => $link->url,
                ];

                return $content;
            }
        }

        $views_monthly_limit = (int)$link_user_plan->views_monthly_limit;

        if ($views_monthly_limit > 0) {
            $startOfMonth = Time::now()->startOfMonth()->format('Y-m-d H:i:s');
            $endOfMonth = Time::now()->endOfMonth()->format('Y-m-d H:i:s');

            $monthly_count = $this->Links->Statistics->find()
                ->where([
                    'Statistics.user_id' => $link->user_id,
                    'Statistics.reason' => 1,
                    'Statistics.created BETWEEN :startOfMonth AND :endOfMonth',
                ])
                ->bind(':startOfMonth', $startOfMonth, 'datetime')
                ->bind(':endOfMonth', $endOfMonth, 'datetime')
                ->count();

            if ($monthly_count >= $views_monthly_limit) {
                // Update link hits
                $this->updateLinkHits($link);
                $this->addNormalStatisticEntry($link, $ad_type, $data, $cookie['ip'], 17);
                $content = [
                    'status' => 'success',
                    //'message' => 'Go without Earn, reached the monthly limit',
                    'message' => '',
                    'url' => $link->url,
                ];

                return $content;
            }
        }

        /**
         * Check for unique visit within last 24 hour
         */
        $startOfToday = Time::today()->format('Y-m-d H:i:s');
        $endOfToday = Time::now()->endOfDay()->format('Y-m-d H:i:s');

        $unique_where = [
            'Statistics.ip' => $cookie['ip'],
            'Statistics.publisher_earn >' => 0,
            'Statistics.created BETWEEN :startOfToday AND :endOfToday',
        ];

        if ($data['mode'] === 'campaign') {
            if (get_option('unique_visitor_per', 'campaign') == 'campaign') {
                $unique_where['Statistics.campaign_id'] = $data['ci'];
            }
        }

        $statistics = $this->Links->Statistics->find()
            ->where($unique_where)
            ->bind(':startOfToday', $startOfToday, 'datetime')
            ->bind(':endOfToday', $endOfToday, 'datetime')
            ->count();

        if ($statistics >= get_option('paid_views_day', 1)) {
            // Update link hits
            $this->updateLinkHits($link);
            $this->addNormalStatisticEntry($link, $ad_type, $data, $cookie['ip'], 7);
            $content = [
                'status' => 'success',
                //'message' => 'Go without Earn because Not unique.',
                'message' => '',
                'url' => $link->url,
            ];

            return $content;
        }

        /**
         * Add statistic record
         */
        $owner_earn = 0;
        if ($data['mode'] === 'campaign') {
            $owner_earn = ($data['advertiser_price'] - $data['publisher_price']) / 1000;
        }

        $publisher_earn = $data['publisher_price'] / 1000;
        if (!empty($link_user_plan->cpm_fixed)) {
            $publisher_earn = $link_user_plan->cpm_fixed / 1000;
        }
        // kind=direct will be a half
        if ($kind == 'direct'){
            $publisher_earn = $publisher_earn / 2;
            $reason = 14;
        } else {
            $reason = 1;
        }

        $user_update = $this->Links->Users->find()->where(['Users.id' => $link->user_id])->first();

        $publisher_user_earnings = true;
        if ($this->Auth->user()) {
            if (get_user_plan($this->Auth->user('id'))->disable_ads) {
                $publisher_user_earnings = false;
            }
        }

        if ($publisher_user_earnings) {
            $user_update->publisher_earnings = price_database_format($user_update->publisher_earnings +
                $publisher_earn);
            $this->Links->Users->save($user_update);
        }

        $referral_id = $referral_earn = 0;
        $enable_referrals = (bool)get_option('enable_referrals', 1);

        if ($enable_referrals && $publisher_user_earnings && !empty($user_update->referred_by)) {
            $user_referred_by = $this->Links->Users->find()
                ->where([
                    'Users.id' => $user_update->referred_by,
                    'Users.status' => 1,
                    'Users.disable_earnings' => 0,
                ])
                ->first();

            if ($user_referred_by) {
                $plan_referral = true;
                if (!get_user_plan($user_referred_by->id)->referral) {
                    $plan_referral = false;
                }

                if (!(float)get_user_plan($user_referred_by->id)->referral_percentage) {
                    $plan_referral = false;
                }

                if ($plan_referral) {
                    $referral_percentage = ((float)get_user_plan($user_referred_by->id)->referral_percentage) / 100;
                    $referral_value = $publisher_earn * $referral_percentage;

                    $user_referred_by->referral_earnings = price_database_format($user_referred_by->referral_earnings +
                        $referral_value);

                    $this->Links->Users->save($user_referred_by);

                    $referral_id = $user_update->referred_by;
                    $referral_earn = $referral_value;
                }
            }
        }

        $country = $this->Links->Statistics->get_country($cookie['ip']);

        $statistic = $this->Links->Statistics->newEntity();

        $statistic->link_id = $link->id;
        $statistic->user_id = $link->user_id;
        $statistic->ad_type = $ad_type;
        $statistic->campaign_id = $data['ci'];
        $statistic->campaign_user_id = $data['cui'];
        $statistic->campaign_item_id = $data['cii'];
        $statistic->ip = $cookie['ip'];
        $statistic->country = $country;
        $statistic->owner_earn = price_database_format($owner_earn - $referral_earn);
        $statistic->publisher_earn = price_database_format($publisher_earn);
        $statistic->referral_id = $referral_id;
        $statistic->referral_earn = price_database_format($referral_earn);
        $statistic->referer_domain = (parse_url($referer_url, PHP_URL_HOST) ?: 'Direct');
        $statistic->referer = $referer_url;
        $statistic->user_agent = env('HTTP_USER_AGENT');
        $statistic->reason = $reason;
        $this->Links->Statistics->save($statistic);

        if ($data['mode'] === 'campaign') {
            /**
             * Update campaign item views and weight
             */
            $campaign_item_update = $CampaignItems->newEntity();
            $campaign_item_update->id = $campaign_item['id'];
            $campaign_item_update->views = $campaign_item['views'] + 1;
            $campaign_item_update->weight = (($campaign_item['views'] + 1) / ($campaign_item['purchase'] * 1000)) * 100;
            $CampaignItems->save($campaign_item_update);

            /**
             * Finish Campaign
             */
            if ($campaign_item_update->weight >= 100) {
                $campaign_weight_items = $CampaignItems->find()
                    ->where([
                        'campaign_id' => $data['ci'],
                        'weight <' => 100,
                    ])
                    ->count();

                if ($campaign_weight_items === 0) {
                    $Campaigns = TableRegistry::getTableLocator()->get('Campaigns');
                    $campaign_complete = $Campaigns->newEntity();
                    $campaign_complete->id = $data['ci'];
                    $campaign_complete->status = 4;
                    $Campaigns->save($campaign_complete);
                }
            }
        }

        // Update link hits
        $this->updateLinkHits($link);
        $content = [
            'status' => 'success',
            //'message' => 'Go With earning :)',
            'message' => '',
            'url' => $link->url,
        ];

        return $content;
    }

    protected function addNormalStatisticEntry($link, $ad_type, $data, $ip, $reason = 0)
    {
        if ((bool)get_option('store_only_paid_clicks_statistics', 0)) {
            return;
        }

        $referer_url = $this->getRefererCookie($link->alias);

        if (!$ip) {
            $ip = get_ip();
        }

        if (empty($data['country'])) {
            $data['country'] = $this->Links->Statistics->get_country(get_ip());
        }

        $statistic = $this->Links->Statistics->newEntity();

        $statistic->link_id = $link->id;
        $statistic->user_id = $link->user_id;
        $statistic->ad_type = $ad_type;
        $statistic->campaign_id = $data['ci'];
        $statistic->campaign_user_id = $data['cui'];
        $statistic->campaign_item_id = $data['cii'];
        $statistic->ip = $ip;
        $statistic->country = $data['country'];
        $statistic->owner_earn = 0;
        $statistic->publisher_earn = 0;
        $statistic->referer_domain = (parse_url($referer_url, PHP_URL_HOST) ?: 'Direct');
        $statistic->referer = $referer_url;
        $statistic->user_agent = env('HTTP_USER_AGENT');
        $statistic->reason = $reason;
        $this->Links->Statistics->save($statistic);
    }

    protected function isRefererBlocked($referer_url)
    {
        $domains = explode(',', get_option('block_referers_domains'));
        $domains = array_map('trim', $domains);
        $domains = array_map('strtolower', $domains);
        $domains = array_filter($domains);

        if (empty($domains)) {
            return false;
        }

        $url_main_domain = strtolower(parse_url($referer_url, PHP_URL_HOST));

        if (in_array($url_main_domain, $domains)) {
            return true;
        }

        $domains = array_filter($domains, function ($value) {
            return substr($value, 0, 2) === "*.";
        });

        if (empty($domains)) {
            return false;
        }

        $domains = array_map(function ($value) {
            return substr($value, 1);
        }, $domains);

        foreach ($domains as $domain) {
            if (preg_match("/" . preg_quote($domain, '/') . "$/", $url_main_domain)) {
                return true;
            }
        }

        return false;
    }

    protected function setVisitorCookie()
    {
        $cookie = $this->Cookie->read('app_visitor');

        if (isset($cookie)) {
            return true;
        }

        $cookie_data = [
            'ip' => get_ip(),
            'date' => (new Time())->toDateTimeString(),
        ];
        $this->Cookie->configKey('app_visitor', [
            'expires' => '+1 day',
            'httpOnly' => true,
        ]);
        $this->Cookie->write('app_visitor', $cookie_data);

        return true;
    }

    /**
     * @param \App\Model\Entity\Link $link
     * @return null
     */
    protected function updateLinkHits($link = null)
    {
        if (!$link) {
            return null;
        }
        $link->hits += 1;
        $link->last_activity = Time::now();
        $link->setDirty('modified', true);
        $this->Links->save($link);

        return null;
    }

    /**
     * @return bool
     */
    protected function isProxy($my_ip = '')
    {
        if (!empty($_SERVER["HTTP_CF_IPCOUNTRY"])) {
            if ($_SERVER["HTTP_CF_IPCOUNTRY"] === 'T1') {
                return true;
            }
        }

        $ip = get_ip();
        if (!empty($my_ip)) $ip = $my_ip;

        $proxy_service = get_option('proxy_service', 'disabled');

        if ($proxy_service === 'disabled') {
            return false;
        }

        if ($proxy_service === 'free') {
            $url = 'https://blackbox.ipinfo.app/lookup/' . urlencode($ip);

            $options = [
                CURLOPT_CONNECTTIMEOUT => 2,
                CURLOPT_TIMEOUT => 2,
                CURLOPT_ENCODING => 'gzip,deflate',
            ];

            $proxy_check = curlRequest($url, 'GET', [], [], $options)->body;

            if (strcasecmp($proxy_check, "Y") === 0) {
                return true;
            }
        }

        if ($proxy_service === 'isproxyip') {
            if (empty(get_option('isproxyip_key'))) {
                return false;
            }

            $url = 'https://api.isproxyip.com/v1/check.php?key=' . urlencode(get_option('isproxyip_key')) . '&ip=' . urlencode($ip);

            $options = [
                CURLOPT_CONNECTTIMEOUT => 2,
                CURLOPT_TIMEOUT => 2,
                CURLOPT_ENCODING => 'gzip,deflate',
            ];

            $proxy_check = curlRequest($url, 'GET', [], [], $options)->body;

            if (strcasecmp($proxy_check, "Y") === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function verifyOnetimeCaptcha()
    {
        if (!isset($_SESSION['onetime_captcha'])) {
            return false;
        }

        $salt = \Cake\Utility\Security::getSalt();
        $onetime_captcha = sha1($salt . get_ip() . $_SERVER['HTTP_USER_AGENT']);

        if ($onetime_captcha === $_SESSION['onetime_captcha']) {
            return true;
        }

        return false;
    }

    protected function displayCaptchaShortlink($plan_disable_captcha, $plan_onetime_captcha)
    {
        if (!isset_captcha()) {
            return false;
        }

        if (get_option('enable_captcha_shortlink') !== 'yes') {
            return false;
        }

        if ($plan_disable_captcha) {
            return false;
        }

        if ($plan_onetime_captcha && $this->verifyOnetimeCaptcha()) {
            return false;
        }

        return true;
    }

    public function shorten()
    {
        $this->autoRender = false;

        $this->setResponse($this->getResponse()->withType('json'));

        if (!$this->getRequest()->is('ajax')) {
            $content = [
                'status' => 'error',
                'message' => __('Bad Request.'),
                'url' => '',
            ];
            $this->getResponse()->body(json_encode($content));

            return $this->response;
        }

        $user_id = 1;
        if (null !== $this->Auth->user('id')) {
            $user_id = $this->Auth->user('id');
        }

        if ($user_id === 1 &&
            (bool)get_option('enable_captcha_shortlink_anonymous', false) &&
            isset_captcha() &&
            !$this->Captcha->verify($this->getRequest()->getData())
        ) {
            $content = [
                'status' => 'error',
                'message' => __('The CAPTCHA was incorrect. Try again'),
                'url' => '',
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->response;
        }

        if ($user_id == 1 && get_option('home_shortening_register') === 'yes') {
            $content = [
                'status' => 'error',
                'message' => __('Bad Request.'),
                'url' => '',
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->response;
        }

        $user = $this->Links->Users->find()->where(['status' => 1, 'id' => $user_id])->first();

        if (!$user) {
            $content = [
                'status' => 'error',
                'message' => __('Invalid user'),
                'url' => '',
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->response;
        }

        $url = trim($this->getRequest()->getData('url'));
        $url = str_replace(" ", "%20", $url);
        $url = parse_url($url, PHP_URL_SCHEME) === null ? 'http://' . $url : $url;
        $this->setRequest($this->getRequest()->withData('url', $url));

        $domain = '';
        if ($this->getRequest()->getData('domain')) {
            $domain = $this->getRequest()->getData('domain');
        }
        if (!in_array($domain, get_multi_domains_list())) {
            $domain = '';
        }

        $linkWhere = [
            'url_hash' => sha1($this->getRequest()->getData('url')),
            'user_id' => $user->id,
            'status' => 1,
            'ad_type' => $this->getRequest()->getData('ad_type'),
            'url' => $this->getRequest()->getData('url'),
        ];

        if ($this->getRequest()->getData('alias') && strlen($this->getRequest()->getData('alias')) > 0) {
            $linkWhere['alias'] = $this->getRequest()->getData('alias');
        }

        $link = $this->Links->find()->where($linkWhere)->first();

        if ($link) {
            $content = [
                'status' => 'success',
                'message' => '',
                'url' => get_short_url($link->alias, $domain),
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->response;
        }

        $user_plan = get_user_plan($user->id);

        if ($user_plan->url_daily_limit) {
            $start = Time::now()->startOfDay()->format('Y-m-d H:i:s');
            $end = Time::now()->endOfDay()->format('Y-m-d H:i:s');

            $links_daily_count = $this->Links->find()
                ->where([
                    'user_id' => $user_id,
                    "created BETWEEN :date1 AND :date2",
                ])
                ->bind(':date1', $start, 'datetime')
                ->bind(':date2', $end, 'datetime')
                ->count();

            if ($links_daily_count >= $user_plan->url_daily_limit) {
                $content = [
                    'status' => 'error',
                    'message' => __('Your account has exceeded its daily created short links limit.'),
                    'url' => '',
                ];
                $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

                return $this->response;
            }
        }

        if ($user_plan->url_monthly_limit) {
            $start = Time::now()->startOfMonth()->format('Y-m-d H:i:s');
            $end = Time::now()->endOfMonth()->format('Y-m-d H:i:s');

            $links_monthly_count = $this->Links->find()
                ->where([
                    'user_id' => $user_id,
                    "created BETWEEN :date1 AND :date2",
                ])
                ->bind(':date1', $start, 'datetime')
                ->bind(':date2', $end, 'datetime')
                ->count();

            if ($links_monthly_count >= $user_plan->url_monthly_limit) {
                $content = [
                    'status' => 'error',
                    'message' => __('Your account has exceeded its monthly created short links limit.'),
                    'url' => '',
                ];
                $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

                return $this->response;
            }
        }

        $link = $this->Links->newEntity();
        $data = [];

        $data['user_id'] = $user->id;
        $data['url'] = $this->getRequest()->getData('url');
        $data['url_hash'] = sha1($this->getRequest()->getData('url'));

        $data['domain'] = $domain;

        if ($user_plan->alias && !empty($this->getRequest()->getData('alias'))) {
            $data['alias'] = $this->getRequest()->getData('alias');
        } else {
            $data['alias'] = $this->Links->geturl();
        }

        $data['ad_type'] = $this->getRequest()->getData('ad_type');
        $link->status = 1;
        $link->hits = 0;
        $link->method = 1;
        $link->last_activity = Time::now();

        $linkMeta = [
            'title' => '',
            'description' => '',
            'image' => '',
        ];

        if ($user_id === 1 && get_option('disable_meta_home') === 'no') {
            $linkMeta = $this->Links->getLinkMeta($this->getRequest()->getData('url'));
        }

        if ($user_id !== 1 && get_option('disable_meta_member') === 'no') {
            $linkMeta = $this->Links->getLinkMeta($this->getRequest()->getData('url'));
        }

        $data['title'] = $linkMeta['title'];
        $data['description'] = $linkMeta['description'];
        $link->image = $linkMeta['image'];

        $link = $this->Links->patchEntity($link, $data);
        if ($this->Links->save($link)) {
            $content = [
                'status' => 'success',
                'message' => '',
                'url' => get_short_url($link->alias, $domain),
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->response;
        }

        $message = __('Invalid URL.');
        if ($link->getErrors()) {
            $error_msg = [];
            foreach ($link->getErrors() as $errors) {
                if (is_array($errors)) {
                    foreach ($errors as $error) {
                        $error_msg[] = $error;
                    }
                } else {
                    $error_msg[] = $errors;
                }
            }

            if (!empty($error_msg)) {
                $message = implode("<br>", $error_msg);
            }
        }

        $content = [
            'status' => 'error',
            'message' => $message,
            'url' => '',
        ];
        $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

        return $this->response;
    }
    protected function apiContent($content = [], $format = 'json')
    {
        $body = json_encode($content);
        if ($format === 'text') {
            $body = $content['confirm_key'];
        }

        return $body;
    }

    public function report(){
        $report = $this->MemberReports->find()->where([
            'traffic_id' => $this->getRequest()->getData('traffic_id'),
            'ip' => $this->getRequest()->getData('ip'),
            'date >=' => date('Y-m-d')
        ])->first();
        if (!$report) $report = $this->MemberReports->newEntity();
        $report = $this->MemberReports->patchEntity($report,$this->getRequest()->getData());
        $report->status = 0;
        $report->date = Time::now();
        if ($this->MemberReports->save($report)){
            $result = [
                'status' => 1,
                'message' => 'Report Success'
            ];
        } else {
            $result = [
                'status' => 0,
                'message' => 'Report Error'
            ];
        }
        $this->setResponse($this->getResponse()->withStringBody(json_encode($result)));
        return $this->response;
    }

    public function getLink($alias){
        /**
         * @var \App\Model\Entity\Link $link
         */
        return $this->Links->find()
            //->contain(['Users'])
            ->contain([
                'Users' => [
                    'fields' => ['id', 'username', 'status', 'disable_earnings'],
                ],
            ])
            ->where([
                'Links.alias' => $alias,
                'Links.status <>' => 3,
                'Users.status' => 1,
            ])
            ->first();
    }

    public function trafficScript(){
        $domains = Cache::read('domains');
        if (empty($domains)){
            $traffics = $this->Traffics->find()
                ->contain(['Datetfs'])
                ->where('status = 1')
                // ->where('Traffics.count_day > Traffics.view_day')
                ->where('Traffics.views <= Traffics.count');
            // print to debug
            $domains = [];
            foreach ($traffics as $traffic){
                $domain = parse_url($traffic->url, PHP_URL_HOST);
                if (!in_array($domain,$domains)){
                    $domains[] = $domain;
                }
            }
            Cache::write('domains',$domains, '1min');
        }

        $found = false;

        foreach ($domains as $domain) {
            if ($domain == ''){
                continue;
            }
            if (str_contains($_SERVER['HTTP_REFERER'], $domain)) {
                $found = true;
                break;
            }
        }
        if ($found === false) die('2');

        $this->layout = 'js';
        $this->autoRender = false;
        $this->setResponse(
            $this->getResponse()
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('X-Robots-Tag', 'noindex, nofollow')
                ->withHeader('Content-Type', 'application/javascript')
        );

        $nJobtf = Cache::read('jobtfs_'.get_ip());
        if ($nJobtf === false) {
            $nJobtf = [];
            $created_check = Time::now()->modify('-12 minutes')->format('Y-m-d H:i:s');
            $jobtf = $this->Jobtfs->find()
                ->contain(['Traffics'])
                ->where([
                    'Jobtfs.ip' => get_ip(),
                    'Jobtfs.status' => 0,
                    'Jobtfs.created >=' => $created_check,
                    //                'Jobtfs.created <=' => date('Y-m-d 23:59:59')
                ])
                ->limit(10)
                ->toArray();
            if (!empty($jobtf)){
                foreach ($jobtf as $item){
                    $nJobtf[] = [
                        'id' => $item->id,
                        'link_url' => $item->link_url,
                        //                    'traffic2_url' => $item->traffic2_url,
                        'traffic_ver2' => $item->traffic->traffic_ver2,
                        'step' => $item->traffic2_step
                    ];
                }
            }
            Cache::write('jobtfs_'.get_ip(), $nJobtf, '5min');
        }

        $traffics_settings = get_option('traffic_default_time','59-60');
        $traffics2_settings = get_option('traffic2_default_time','79-80');
        $traffics2_url_settings = get_option('traffic2_url_default_time','10-15');

        $traffics_time = $this->getRandomTime($traffics_settings);
        $traffic2_time = $this->getRandomTime($traffics2_settings);
        $traffics2_url_time = $this->getRandomTime($traffics2_url_settings);

        $this->set('jobtf',$nJobtf);
        $this->set('traffics_settings',$traffics_time);
        $this->set('traffics2_settings',$traffic2_time);
        $this->set('traffics2_url_settings',$traffics2_url_time);
        $this->render('mneylink_js');
    }

    public function sendError($mes){
        return [
            'status' => 0,
            'message' => $mes
        ];
    }

    public function sendSuccess($mes,$data = []){
        return [
            'status' => 1,
            'message' => $mes,
            'data' => $data
        ];
    }

    public function updateStep(){
        $this->autoRender = false;
        $this->setResponse(
            $this->getResponse()
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('X-Robots-Tag', 'noindex, nofollow')
        );
        $jobtf_id = $this->getRequest()->getQuery('id');
        if (empty($jobtf_id)) return false;
        $jobtf = $this->Jobtfs->find()
            ->where(['id' => $jobtf_id])
            ->first();

        //check Time
        $traffics2_times = get_option('traffic2_default_time','79-80');
        $traffics2_times = explode('-',$traffics2_times);

        $dataCheck = Cache::read('date_'.get_ip());
        if (empty($dataCheck)) return false;
        $timeCheck = $dataCheck['time_check'];
        $timeDown = strtotime(Time::now()->format('Y-m-d H:i:s')) - strtotime(Time::parse($timeCheck)->format('Y-m-d H:i:s'));

        if ($timeDown >= min($traffics2_times)){
            $jobtf->traffic2_step = 2;
            $this->Jobtfs->save($jobtf);
            $content = $this->sendSuccess('CLICK VÀO LINK BẤT KỲ </br> TRÊN TRANG ĐỂ LẤY MÃ');
        } else {
            $content = $this->sendError('Lỗi! Hãy thử lại');
        }

        $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));
        return $this->getResponse();
    }

    public function getRandomTime($time){
        $timeEX = explode('-',$time);
        return rand($timeEX[0],$timeEX[1]);
    }

    public function testServer(){
        $this->autoRender = false;
        if ($this->Auth->user('role') != 'admin') return false;
//        Configure::write('debug', 2);

        // Store a string into the variable which
// needs to be encrypted
        $simple_string = "Welcome to GeeksforGeeks";

// Display the original string
        echo "Original String: " . $simple_string . "\n";

// Store cipher method
        $ciphering = "BF-CBC";

// Use OpenSSL encryption method
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;

// Use random_bytes() function to generate a random initialization vector (iv)
        $encryption_iv = random_bytes($iv_length);

// Alternatively, you can use a fixed iv if needed
// $encryption_iv = openssl_random_pseudo_bytes($iv_length);

// Use php_uname() as the encryption key
        $encryption_key = openssl_digest(php_uname(), 'MD5', TRUE);

// Encryption process
        $encryption = openssl_encrypt($simple_string, $ciphering,
            $encryption_key, $options, $encryption_iv);

// Display the encrypted string
        echo "Encrypted String: " . $encryption . "\n";

// Decryption process
        $decryption = openssl_decrypt($encryption, $ciphering,
            $encryption_key, $options, $encryption_iv);

// Display the decrypted string
        echo "Decrypted String: " . $decryption;

        // Get all the ciphers
        $ciphers = openssl_get_cipher_methods();

// Output the cipher to screen
        print("<pre>".print_r($ciphers, true)."</pre>");
    }
}
