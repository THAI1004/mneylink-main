<?php

namespace App\Controller;

use App\Model\Table\ErrorLogsTable;
use App\Model\Table\JobtfsTable;
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\I18n\FrozenDate;
use Cake\I18n\Time;

/**
 * @property \App\Model\Table\LinksTable $Links
 * @property \Cake\ORM\Table $Tools
 * @property JobtfsTable $Jobtfs
 * @property ErrorLogsTable $ErrorLogs
 */
class ToolsController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['st', 'full', 'api', 'bookmarklet','loadTraffic','countdown','clearOpcache']);
    }

    public function bookmarklet()
    {
        $this->setResponse(
            $this->getResponse()->withHeader('X-Robots-Tag', 'noindex, nofollow')
        );

        $this->viewBuilder()->setLayout('blank');

        $this->loadModel('Links');

        $valid_bookmarklet = false;

        if ($this->getRequest()->getQuery('api') &&
            $this->getRequest()->getQuery('url')
        ) {
            $valid_bookmarklet = true;
        }

        $this->set('valid_bookmarklet', $valid_bookmarklet);

        if (!$valid_bookmarklet) {
            $this->Flash->error(__('Bad Request.'));

            return null;
        }

        $api = $this->getRequest()->getQuery('api');

        /**
         * @var \App\Model\Entity\User $user
         */
        $user = $this->Links->Users->find()
            ->contain('Plans')
            ->where([
                'Users.api_token' => $api,
                'Users.status' => 1,
            ])
            ->first();

        if (!$user) {
            $this->Flash->error(__('Invalid API token.'));
            $valid_bookmarklet = false;
            $this->set('valid_bookmarklet', $valid_bookmarklet);

            return null;
        }

        $user_plan = get_user_plan($user->id);

        if (!$user_plan->bookmarklet) {
            $this->Flash->error(__('You must upgrade your plan so you can use this tool.'));
            $valid_bookmarklet = false;
            $this->set('valid_bookmarklet', $valid_bookmarklet);

            return null;
        }

        $custom_alias = (bool)$user_plan->alias;
        $this->set('custom_alias', $custom_alias);

        $link = $this->Links->newEntity();

        if ($this->getRequest()->is('post')) {
            $url = $this->getRequest()->getData('url');

            $ad_type = get_option('member_default_advert', 1);
            if ($this->getRequest()->getData('ad_type')) {
                if (array_key_exists($this->getRequest()->getData('ad_type'), get_allowed_ads())) {
                    $ad_type = $this->getRequest()->getData('ad_type');
                }
            }

            $url = trim($url);
            $url = str_replace(" ", "%20", $url);
            $url = parse_url($url, PHP_URL_SCHEME) === null ? 'http://' . $url : $url;

            // Check if the URL is already a short link
            $url_domain = parse_url($url, PHP_URL_HOST);

            $system_domains = array_values(get_all_domains_list());

            if (in_array($url_domain, $system_domains)) {
                $url_path = parse_url($url, PHP_URL_PATH);
                $path_array = explode('/', $url_path);
                $alias = end($path_array);

                $link = $this->Links->find()
                    ->where([
                        'alias' => $alias,
                    ])
                    ->first();

                if ($link) {
                    $this->set('short_link', get_short_url($link->alias, $link->domain));

                    return null;
                }
            }

            $domain = '';
            if ($this->getRequest()->getData('domain')) {
                $domain = $this->getRequest()->getData('domain');
            }
            if (!in_array($domain, get_multi_domains_list())) {
                $domain = '';
            }

            $linkWhere = [
                'url_hash' => sha1($url),
                'user_id' => $user->id,
                'status' => 1,
                'ad_type' => $ad_type,
                'url' => $url,
            ];

            if ($this->getRequest()->getQuery('alias') && strlen($this->getRequest()->getQuery('alias')) > 0) {
                $linkWhere['alias'] = $this->getRequest()->getData('alias');
            }

            $link = $this->Links->find()->where($linkWhere)->first();

            if ($link) {
                $this->set('short_link', get_short_url($link->alias, $domain));

                return null;
            }

            if ($user_plan->url_daily_limit) {
                $start = Time::now()->startOfDay()->format('Y-m-d H:i:s');
                $end = Time::now()->endOfDay()->format('Y-m-d H:i:s');

                $links_daily_count = $this->Links->find()
                    ->where([
                        'user_id' => $user->id,
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

                    return $this->getResponse();
                }
            }

            if ($user_plan->url_monthly_limit) {
                $start = Time::now()->startOfMonth()->format('Y-m-d H:i:s');
                $end = Time::now()->endOfMonth()->format('Y-m-d H:i:s');

                $links_monthly_count = $this->Links->find()
                    ->where([
                        'user_id' => $user->id,
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

                    return $this->getResponse();
                }
            }

            $link = $this->Links->newEntity();
            $data = [];

            $data['user_id'] = $user->id;
            $data['url'] = $url;
            $data['url_hash'] = sha1($url);
            $data['domain'] = $domain;
            if (empty($this->getRequest()->getData('alias'))) {
                $data['alias'] = $this->Links->geturl();
            } else {
                $data['alias'] = $this->getRequest()->getData('alias');
            }
            $data['ad_type'] = $ad_type;

            $link->status = 1;
            $link->hits = 0;
            $link->method = 6;

            $linkMeta = [
                'title' => '',
                'description' => '',
                'image' => '',
            ];

            if (get_option('disable_meta_api') === 'no') {
                $linkMeta = $this->Links->getLinkMeta($url);
            }

            $data['title'] = $linkMeta['title'];
            $data['description'] = $linkMeta['description'];
            $link->image = $linkMeta['image'];

            $link = $this->Links->patchEntity($link, $data);
            if ($this->Links->save($link)) {
                $this->set('short_link', get_short_url($link->alias, $domain));

                return null;
            } else {
                $this->Flash->error(__('Check the below errors.'));
            }
        }
        $this->set('link', $link);
    }

    public function st()
    {
        $this->setResponse(
            $this->getResponse()->withHeader('X-Robots-Tag', 'noindex, nofollow')
        );

        $this->viewBuilder()->setLayout('blank');

        $this->loadModel('Links');

        $message = '';
        $this->set('message', $message);

        if (!$this->getRequest()->is(['post'])) {
            return null;
        }

        if (!$this->getRequest()->getData('api') ||
            !$this->getRequest()->getData('url')
        ) {
            $message = __('Invalid Request.');
            $this->set('message', $message);

            return null;
        }

        $api = $this->getRequest()->getData('api');
        $url = $this->getRequest()->getData('url');

        $ad_type = get_option('member_default_advert', 1);

        /**
         * @var \App\Model\Entity\User $user
         */
        $user = $this->Links->Users->find()
            ->contain('Plans')
            ->where([
                'Users.api_token' => $api,
                'Users.status' => 1,
            ])
            ->first();

        if (!$user) {
            $message = __('Invalid API token.');
            $this->set('message', $message);

            return null;
        }

        $user_plan = get_user_plan($user->id);

        if (!$user_plan->api_quick) {
            $message = __('You must upgrade your plan so you can use this tool.');
            $this->set('message', $message);

            return null;
        }

        $url = trim($url);
        $url = str_replace(" ", "%20", $url);
        $url = parse_url($url, PHP_URL_SCHEME) === null ? 'http://' . $url : $url;

        // Check if the URL is already a short link
        $url_domain = parse_url($url, PHP_URL_HOST);

        $system_domains = array_values(get_all_domains_list());

        if (in_array($url_domain, $system_domains)) {
            $url_path = parse_url($url, PHP_URL_PATH);
            $path_array = explode('/', $url_path);
            $alias = end($path_array);

            $link = $this->Links->find()
                ->where([
                    'alias' => $alias,
                ])
                ->first();

            if ($link) {
                return $this->redirect(get_short_url($link->alias), 301);
            }
        }

        $link = $this->Links->find()
            ->where([
                'url_hash' => sha1($url),
                'user_id' => $user->id,
                'status' => 1,
                'ad_type' => $ad_type,
                'url' => $url,
            ])
            ->first();

        if ($link) {
            return $this->redirect(get_short_url($link->alias), 301);
        }

        if ($user_plan->url_daily_limit) {
            $start = Time::now()->startOfDay()->format('Y-m-d H:i:s');
            $end = Time::now()->endOfDay()->format('Y-m-d H:i:s');

            $links_daily_count = $this->Links->find()
                ->where([
                    'user_id' => $user->id,
                    "created BETWEEN :date1 AND :date2",
                ])
                ->bind(':date1', $start, 'datetime')
                ->bind(':date2', $end, 'datetime')
                ->count();

            if ($links_daily_count >= $user_plan->url_daily_limit) {
                $message = __('Your account has exceeded its daily created short links limit.');
                $this->set('message', $message);

                return null;
            }
        }

        if ($user_plan->url_monthly_limit) {
            $start = Time::now()->startOfMonth()->format('Y-m-d H:i:s');
            $end = Time::now()->endOfMonth()->format('Y-m-d H:i:s');

            $links_monthly_count = $this->Links->find()
                ->where([
                    'user_id' => $user->id,
                    "created BETWEEN :date1 AND :date2",
                ])
                ->bind(':date1', $start, 'datetime')
                ->bind(':date2', $end, 'datetime')
                ->count();

            if ($links_monthly_count >= $user_plan->url_monthly_limit) {
                $message = __('Your account has exceeded its monthly created short links limit.');
                $this->set('message', $message);

                return null;
            }
        }

        $link = $this->Links->newEntity();
        $data = [];

        $data['user_id'] = $user->id;
        $data['url'] = $url;
        $data['url_hash'] = sha1($url);
        $data['alias'] = $this->Links->geturl();
        $data['ad_type'] = $ad_type;

        $link->status = 1;
        $link->hits = 0;
        $link->method = 2;

        $linkMeta = [
            'title' => '',
            'description' => '',
            'image' => '',
        ];

        if (get_option('disable_meta_api') === 'no') {
            $linkMeta = $this->Links->getLinkMeta($url);
        }

        $data['title'] = $linkMeta['title'];
        $data['description'] = $linkMeta['description'];
        $link->image = $linkMeta['image'];

        $link = $this->Links->patchEntity($link, $data);
        if ($this->Links->save($link)) {
            return $this->redirect(get_short_url($link->alias), 301);
        }

        $error_msg = [];
        if ($link->hasErrors()) {
            foreach ($link->getErrors() as $errors) {
                if (is_array($errors)) {
                    foreach ($errors as $error) {
                        $error_msg[] = h($error);
                    }
                } else {
                    $error_msg[] = h($errors);
                }
            }
        }
        $this->set('message', implode('<br>', $error_msg));

        return null;
    }

    public function full()
    {
        $this->setResponse(
            $this->getResponse()
                ->withHeader('X-Robots-Tag', 'noindex, nofollow')
        );

        $this->viewBuilder()->setLayout('blank');

        $this->loadModel('Links');

        $message = '';
        $this->set('message', $message);

        if (!$this->getRequest()->getQuery('api') ||
            !$this->getRequest()->getQuery('url')
        ) {
            $message = __('Invalid Request.');
            $this->set('message', $message);

            return null;
        }

        $api = $this->getRequest()->getQuery('api');
        $url = urldecode(base64_decode($this->getRequest()->getQuery('url')));

        $ad_type = get_option('member_default_advert', 1);
        if (array_key_exists($this->getRequest()->getQuery('type'), get_allowed_ads())) {
            $ad_type = $this->getRequest()->getQuery('type');
        }

        /**
         * @var \App\Model\Entity\User $user
         */
        $user = $this->Links->Users->find()
            ->contain('Plans')
            ->where([
                'Users.api_token' => $api,
                'Users.status' => 1,
            ])
            ->first();

        if (!$user) {
            $message = __('Invalid API token.');
            $this->set('message', $message);

            return null;
        }

        $user_plan = get_user_plan($user->id);

        if (!$user_plan->api_full) {
            $message = __('You must upgrade your plan so you can use this tool.');
            $this->set('message', $message);

            return null;
        }

        $url = trim($url);
        $url = str_replace(" ", "%20", $url);
        $url = parse_url($url, PHP_URL_SCHEME) === null ? 'http://' . $url : $url;

        // Check if the URL is already a short link
        $url_domain = parse_url($url, PHP_URL_HOST);

        $system_domains = array_values(get_all_domains_list());

        if (in_array($url_domain, $system_domains)) {
            $url_path = parse_url($url, PHP_URL_PATH);
            $path_array = explode('/', $url_path);
            $alias = end($path_array);

            $link = $this->Links->find()
                ->where([
                    'alias' => $alias,
                ])
                ->first();

            if ($link) {
                return $this->redirect(get_short_url($link->alias), 301);
            }
        }

        $linkWhere = [
            'url_hash' => sha1($url),
            'user_id' => $user->id,
            'status' => 1,
            'ad_type' => $ad_type,
            'url' => $url,
        ];

        $link = $this->Links->find()->where($linkWhere)->first();

        if ($link) {
            return $this->redirect(get_short_url($link->alias), 301);
        }

        if ($user_plan->url_daily_limit) {
            $start = Time::now()->startOfDay()->format('Y-m-d H:i:s');
            $end = Time::now()->endOfDay()->format('Y-m-d H:i:s');

            $links_daily_count = $this->Links->find()
                ->where([
                    'user_id' => $user->id,
                    "created BETWEEN :date1 AND :date2",
                ])
                ->bind(':date1', $start, 'datetime')
                ->bind(':date2', $end, 'datetime')
                ->count();

            if ($links_daily_count >= $user_plan->url_daily_limit) {
                $message = __('Your account has exceeded its daily created short links limit.');
                $this->set('message', $message);

                return null;
            }
        }

        if ($user_plan->url_monthly_limit) {
            $start = Time::now()->startOfMonth()->format('Y-m-d H:i:s');
            $end = Time::now()->endOfMonth()->format('Y-m-d H:i:s');

            $links_monthly_count = $this->Links->find()
                ->where([
                    'user_id' => $user->id,
                    "created BETWEEN :date1 AND :date2",
                ])
                ->bind(':date1', $start, 'datetime')
                ->bind(':date2', $end, 'datetime')
                ->count();

            if ($links_monthly_count >= $user_plan->url_monthly_limit) {
                $message = __('Your account has exceeded its monthly created short links limit.');
                $this->set('message', $message);

                return null;
            }
        }

        $link = $this->Links->newEntity();
        $data = [];

        $data['user_id'] = $user->id;
        $data['url'] = $url;
        $data['url_hash'] = sha1($url);
        $data['alias'] = $this->Links->geturl();
        $data['ad_type'] = $ad_type;

        $link->status = 1;
        $link->hits = 0;
        $link->method = 4;

        $linkMeta = [
            'title' => '',
            'description' => '',
            'image' => '',
        ];

        if (get_option('disable_meta_api') === 'no') {
            $linkMeta = $this->Links->getLinkMeta($url);
        }

        $data['title'] = $linkMeta['title'];
        $data['description'] = $linkMeta['description'];
        $link->image = $linkMeta['image'];

        $link = $this->Links->patchEntity($link, $data);
        if ($this->Links->save($link)) {
            return $this->redirect(get_short_url($link->alias), 301);
        }

        $error_msg = [];
        if ($link->hasErrors()) {
            foreach ($link->getErrors() as $errors) {
                if (is_array($errors)) {
                    foreach ($errors as $error) {
                        $error_msg[] = h($error);
                    }
                } else {
                    $error_msg[] = h($errors);
                }
            }
        }
        $this->set('message', implode('<br>', $error_msg));

        return null;
    }

    public function api()
    {
        $this->autoRender = false;

        $this->setResponse(
            $this->getResponse()
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('X-Robots-Tag', 'noindex, nofollow')
        );

        $this->loadModel('Links');

        $format = 'json';
        if ($this->getRequest()->getQuery('format') && strtolower($this->getRequest()->getQuery('format')) === 'text') {
            $format = 'text';
        }
        $this->setResponse($this->getResponse()->withType($format));

        if (!$this->getRequest()->getQuery('api') ||
            !$this->getRequest()->getQuery('url')
        ) {
            $content = [
                'status' => 'error',
                'message' => 'Invalid API call',
                'shortenedUrl' => '',
            ];
            $this->setResponse($this->getResponse()->withStringBody($this->apiContent($content, $format)));

            return $this->response;
        }

        $api = $this->getRequest()->getQuery('api');
        $url = urldecode($this->getRequest()->getQuery('url'));

        $ad_type = get_option('member_default_advert', 1);
        if (array_key_exists($this->getRequest()->getQuery('type'), get_allowed_ads())) {
            $ad_type = $this->getRequest()->getQuery('type');
        }

        /**
         * @var \App\Model\Entity\User $user
         */
        $user = $this->Links->Users->find()
            ->contain('Plans')
            ->where([
                'Users.api_token' => $api,
                'Users.status' => 1,
            ])
            ->first();

        if (!$user) {
            $content = [
                'status' => 'error',
                'message' => 'Invalid API token',
                'shortenedUrl' => '',
            ];
            $this->setResponse($this->getResponse()->withStringBody($this->apiContent($content, $format)));

            return $this->getResponse();
        }

        $user_plan = get_user_plan($user->id);

        if (!$user_plan->api_developer) {
            $content = [
                'status' => 'error',
                'message' => 'You must upgrade your plan so you can use this tool.',
                'shortenedUrl' => '',
            ];
            $this->setResponse($this->getResponse()->withStringBody($this->apiContent($content, $format)));

            return $this->getResponse();
        }

        $url = trim($url);
        $url = str_replace(" ", "%20", $url);
        $url = parse_url($url, PHP_URL_SCHEME) === null ? 'http://' . $url : $url;

        // Check if the URL is already a short link
        $url_domain = parse_url($url, PHP_URL_HOST);

        $system_domains = array_values(get_all_domains_list());

        if (in_array($url_domain, $system_domains)) {
            $url_path = parse_url($url, PHP_URL_PATH);
            $path_array = explode('/', $url_path);
            $alias = end($path_array);

            $link = $this->Links->find()
                ->where([
                    'alias' => $alias,
                ])
                ->first();

            if ($link) {
                $content = [
                    'status' => 'success',
                    'shortenedUrl' => get_short_url($link->alias, $link->domain),
                ];
                $this->setResponse($this->getResponse()->withStringBody($this->apiContent($content, $format)));

                return $this->getResponse();
            }
        }

        $domain = '';
        if ($this->getRequest()->getQuery('domain')) {
            $domain = $this->getRequest()->getQuery('domain');
        }
        if (!in_array($domain, get_multi_domains_list())) {
            $domain = '';
        }

        $linkWhere = [
            'url_hash' => sha1($url),
            'user_id' => $user->id,
            'status' => 1,
            'ad_type' => $ad_type,
            'url' => $url,
        ];

        if ($this->getRequest()->getQuery('alias') && strlen($this->getRequest()->getQuery('alias')) > 0) {
            $linkWhere['alias'] = $this->getRequest()->getQuery('alias');
        }

        $link = $this->Links->find()->where($linkWhere)->first();

        if ($link) {
            $content = [
                'status' => 'success',
                'shortenedUrl' => get_short_url($link->alias, $link->domain),
            ];
            $this->setResponse($this->getResponse()->withStringBody($this->apiContent($content, $format)));

            return $this->getResponse();
        }

        if ($user_plan->url_daily_limit) {
            $start = Time::now()->startOfDay()->format('Y-m-d H:i:s');
            $end = Time::now()->endOfDay()->format('Y-m-d H:i:s');

            $links_daily_count = $this->Links->find()
                ->where([
                    'user_id' => $user->id,
                    "created BETWEEN :date1 AND :date2",
                ])
                ->bind(':date1', $start, 'datetime')
                ->bind(':date2', $end, 'datetime')
                ->count();

            if ($links_daily_count >= $user_plan->url_daily_limit) {
                $content = [
                    'status' => 'error',
                    'message' => 'Your account has exceeded its daily created short links limit.',
                    'shortenedUrl' => '',
                ];
                $this->setResponse($this->getResponse()->withStringBody($this->apiContent($content, $format)));

                return $this->getResponse();
            }
        }

        if ($user_plan->url_monthly_limit) {
            $start = Time::now()->startOfMonth()->format('Y-m-d H:i:s');
            $end = Time::now()->endOfMonth()->format('Y-m-d H:i:s');

            $links_monthly_count = $this->Links->find()
                ->where([
                    'user_id' => $user->id,
                    "created BETWEEN :date1 AND :date2",
                ])
                ->bind(':date1', $start, 'datetime')
                ->bind(':date2', $end, 'datetime')
                ->count();

            if ($links_monthly_count >= $user_plan->url_monthly_limit) {
                $content = [
                    'status' => 'error',
                    'message' => 'Your account has exceeded its monthly created short links limit.',
                    'shortenedUrl' => '',
                ];
                $this->setResponse($this->getResponse()->withStringBody($this->apiContent($content, $format)));

                return $this->getResponse();
            }
        }

        $link = $this->Links->newEntity();
        $data = [];

        $data['user_id'] = $user->id;
        $data['url'] = $url;
        $data['url_hash'] = sha1($url);
        $data['domain'] = $domain;
        if ($user_plan->alias && !empty($this->getRequest()->getQuery('alias'))) {
            $data['alias'] = $this->getRequest()->getQuery('alias');
        } else {
            $data['alias'] = $this->Links->geturl();
        }

        $data['ad_type'] = $ad_type;

        $link->status = 1;
        $link->hits = 0;
        $link->method = 5;

        $linkMeta = [
            'title' => '',
            'description' => '',
            'image' => '',
        ];

        if (get_option('disable_meta_api') === 'no') {
            $linkMeta = $this->Links->getLinkMeta($url);
        }

        $data['title'] = $linkMeta['title'];
        $data['description'] = $linkMeta['description'];
        $link->image = $linkMeta['image'];

        $link = $this->Links->patchEntity($link, $data);

        if ($this->Links->save($link)) {
            $content = [
                'status' => 'success',
                'message' => '',
                'shortenedUrl' => get_short_url($link->alias, $link->domain),
            ];
            $this->setResponse($this->getResponse()->withStringBody($this->apiContent($content, $format)));

            return $this->getResponse();
        }

        $error_msg = [];
        if ($link->hasErrors()) {
            foreach ($link->getErrors() as $errors) {
                if (is_array($errors)) {
                    foreach ($errors as $error) {
                        $error_msg[] = $error;
                    }
                } else {
                    $error_msg[] = $errors;
                }
            }
        }

        $content = [
            'status' => 'error',
            'message' => $error_msg,
            'shortenedUrl' => '',
        ];
        $this->setResponse($this->getResponse()->withStringBody($this->apiContent($content, $format)));

        return $this->getResponse();
    }

    protected function apiContent($content = [], $format = 'json')
    {
        $body = json_encode($content);
        if ($format === 'text') {
            $body = $content['shortenedUrl'];
        }

        return $body;
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

    public function countdown_old(){
        $this->autoRender = false;
        $this->setResponse(
            $this->getResponse()
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('X-Robots-Tag', 'noindex, nofollow')
        );
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $time = $this->getRequest()->getQuery('t');
        if (empty($time)) return false;
        $result = [
            'time_check' => date('Y-m-d H:i:s'),
            'url' => $_SERVER['HTTP_ORIGIN']
        ];
        Cache::write('date_'.get_ip(),date('Y-m-d H:i:s'));
        $this->setResponse($this->getResponse()->withStringBody(json_encode(['status' => 0])));

        return $this->getResponse();
    }
    public function countdown(){
        $this->autoRender = false;
        $this->setResponse(
            $this->getResponse()
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('X-Robots-Tag', 'noindex, nofollow')
        );
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $time = $this->getRequest()->getQuery('t');
        if (empty($time)) return false;
        $result = [
            'time_check' => date('Y-m-d H:i:s'),
            'url' => (!empty($_SERVER['HTTP_ORIGIN'])) ? $_SERVER['HTTP_ORIGIN'] : false
        ];
        Cache::write('date_'.get_ip(),$result);
        $this->setResponse($this->getResponse()->withStringBody(json_encode(['status' => 0])));

        return $this->getResponse();
    }

    public function loadTraffic_old(){
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $this->autoRender = false;
        $this->setResponse(
            $this->getResponse()
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('X-Robots-Tag', 'noindex, nofollow')
        );

        $this->loadModel('Jobtfs');
        $this->loadModel('Traffics');
        $w = $this->getRequest()->getQuery('w');
        $w2 = $this->getRequest()->getQuery('w2');
        $time = $this->getRequest()->getQuery('t');
        $traffics_settings = get_option('traffic_default_time','59-60');
        $traffics2_settings = get_option('traffic2_default_time','79-80');
        $traffics2Url_settings = get_option('traffic2_url_default_time','10-15');
        $timeTraffics = explode('-',$traffics_settings);
        $timeTraffics2 = explode('-',$traffics2_settings);
        $timeTraffics2Url = explode('-',$traffics2Url_settings);
        $id = $this->getRequest()->getQuery('ti');

        if (!empty($w)){
            $created_check = Time::now()->modify('-20 minutes')->format('Y-m-d H:i:s');
            $condition = [
                'Jobtfs.ip' => get_ip(),
                'Jobtfs.link_url LIKE' => "%$w%",
                'Jobtfs.status' => 0,
                'Jobtfs.created >=' => $created_check,
            ];
            if($id){
                $condition['Jobtfs.id'] = $id;
            }
            $jobtf = $this->Jobtfs->find()
                ->contain(['Traffics'])
                ->where($condition)
                ->first();
            if (!$jobtf){
                $mes = 'Lỗi! Hãy thử lại #1';
                $content = [
                    'status' => 0,
                    'message' => $mes
                ];
            } else {
                $continue = 0;
                $timeCheck = Cache::read('date_'.get_ip());
                if (empty($timeCheck)) return false;
                $timeDown = strtotime(Time::now()->format('Y-m-d H:i:s')) - strtotime(Time::parse($timeCheck)->format('Y-m-d H:i:s'));

                $checkTimeDown = 0;
                if (!$jobtf->traffic->traffic_ver2){
                    $checkTimeDown = min($timeTraffics);
                } else {
                    $checkTimeDown = min($timeTraffics2);
                    if ($jobtf->traffic2_step == 2) $checkTimeDown = min($timeTraffics2Url);
                }
                if ($timeDown >= $checkTimeDown) $continue = 1;

                if ($continue){
                    $mes = 'success';
                    $content = [
                        'status' => 1,
                        'message' => $mes,
                        'data' => ['html' => $jobtf->confirm_key]
                    ];
                } else {
                    $mes = 'Lỗi! Hãy thử lại #3';
                    $content = [
                        'status' => 0,
                        'message' => $mes
                    ];
                }
                Cache::delete('date_'.get_ip());
            }
        }
        $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

        return $this->getResponse();
    }
    public function loadTraffic(){
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $this->autoRender = false;
        $this->setResponse(
            $this->getResponse()
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('X-Robots-Tag', 'noindex, nofollow')
        );

        $this->loadModel('Jobtfs');
        $this->loadModel('Traffics');
        $r = $this->getRequest()->getQuery('r');
        $r = rtrim($r, '/');
        $w = $this->getRequest()->getQuery('w');
        $w = rtrim($w, '/');
        $w2 = $this->getRequest()->getQuery('w2');
        $time = $this->getRequest()->getQuery('t');
        $traffics_settings = get_option('traffic_default_time','59-60');
        $traffics2_settings = get_option('traffic2_default_time','79-80');
        $traffics2Url_settings = get_option('traffic2_url_default_time','10-15');
        $timeTraffics = explode('-',$traffics_settings);
        $timeTraffics2 = explode('-',$traffics2_settings);
        $timeTraffics2Url = explode('-',$traffics2Url_settings);
        $id = $this->getRequest()->getQuery('ti');

        if (!empty($w)){
            $created_check = Time::now()->modify('-20 minutes')->format('Y-m-d H:i:s');
            $condition = [
                'Jobtfs.ip' => get_ip(), // IP không trùng với trang url và google
                'Jobtfs.link_url LIKE' => "%$w%", // URL target không trùng với đăng ký
                'Jobtfs.status' => 0,             // Đã hết mã (traffic)
                'Jobtfs.created >=' => $created_check, // Hết thời gian quá 20 phút
            ];
            if($id){
                $condition['Jobtfs.id'] = $id;
            }
            $jobtf = $this->Jobtfs->find()
                ->contain(['Traffics'])
                ->where($condition)
                ->first();

            // Kiểm tra nếu kind ko phải là direct, thì phải có referer từ google.com
            if ($jobtf->traffic->kind == 'google'){
                if (strpos($r, 'google') == false) {
                    $mes = 'Lấy link không hợp lệ #4!';
                    $content = [
                        'status' => 0,
                        'message' => $mes
                    ];
                    $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));
                    return $this->getResponse();
                }
            }

            if (!$jobtf){
                $mes = 'Lấy link không hợp lệ #1!';
                $content = [
                    'status' => 0,
                    'message' => $mes
                ];
            } else {
                $continue = 0;
                $dataCheck = Cache::read('date_'.get_ip());
                if (empty($dataCheck)) {
                    $content = [
                        'status' => 0,
                        'message' => "Lấy link không hợp lệ #3!"
                    ];
                    $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));
                }
                $timeCheck = $dataCheck['time_check'];
                $dataUrl = $dataCheck['url'];
                if (!$dataUrl){
                    $content = [
                        'status' => 0,
                        'message' => "Lấy link không hợp lệ #2!"
                    ];
                    $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));
                }

                $timeDown = strtotime(Time::now()->format('Y-m-d H:i:s')) - strtotime(Time::parse($timeCheck)->format('Y-m-d H:i:s'));

                $checkTimeDown = 0;
                if (!$jobtf->traffic->traffic_ver2){
                    $checkTimeDown = min($timeTraffics);
                } else {
                    $checkTimeDown = min($timeTraffics2);
                    if ($jobtf->traffic2_step == 2) $checkTimeDown = min($timeTraffics2Url);
                }
                if ($timeDown >= $checkTimeDown) $continue = 1;

                if ($continue){
                    $mes = 'success';
                    $content = [
                        'status' => 1,
                        'message' => $mes,
                        'data' => ['html' => $jobtf->confirm_key]
                    ];
                } else {
                    $mes = 'Lỗi! Hãy thử lại #5';
                    $content = [
                        'status' => 0,
                        'message' => $mes
                    ];
                }
                Cache::delete('date_'.get_ip());
            }
        }
        $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

        return $this->getResponse();
    }

    public function addLog($mes,$user_id = null,$traffic_id = null){
        $this->loadModel('ErrorLogs');
        if (empty($mes)) return false;
        $logData = $this->ErrorLogs->newEntity();
        $logData->ip = get_ip();
        $logData->traffic_id = $traffic_id;
        $logData->user_id = $user_id;
        $logData->content = $mes;
        $logData->created_at = date('Y-m-d H:i:s');
        return $this->ErrorLogs->save($logData);
    }

    public function clearOpcache(){
        if (function_exists('opcache_reset') ) {
            opcache_reset();
            $this->setResponse($this->getResponse()->withStringBody("OPCache Reset Exists\n"));
        } else {
            $this->setResponse($this->getResponse()->withStringBody("OPCache does not exist\n"));
        }
        return $this->getResponse();
    }
}
