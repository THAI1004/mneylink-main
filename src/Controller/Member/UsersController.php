<?php

namespace App\Controller\Member;

use Cake\Mailer\MailerAwareTrait;
use Cake\I18n\Time;
use Cake\Http\Exception\NotFoundException;
use Cake\Cache\Cache;
use Cake\Datasource\ConnectionManager;

/**
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\AnnouncementsTable $Announcements
 */
class UsersController extends AppMemberController
{
    use MailerAwareTrait;

    public function dashboard()
    {
        $auth_user_id = $this->Auth->user('id');

        $last_record = Time::now();
        $first_record = user()->created;

        $year_month = [];

        $last_month = Time::now()->year($last_record->year)->month($last_record->month)->startOfMonth();
        //        $first_month = Time::now()->year($first_record->year)->month($first_record->month)->startOfMonth();
        $first_month = Time::now()->modify('-1 month')->startOfMonth();

        while ($first_month <= $last_month) {
            $year_month[$last_month->format('Y-m')] = $last_month->i18nFormat('LLLL Y');

            $last_month->modify('-1 month');
        }

        $this->set('year_month', $year_month);

        $to_month = Time::now()->format('Y-m');
        if (
            $this->getRequest()->getQuery('month') &&
            array_key_exists($this->getRequest()->getQuery('month'), $year_month)
        ) {
            $to_month = explode('-', $this->getRequest()->getQuery('month'));
            $year = (int)$to_month[0];
            $month = (int)$to_month[1];
        } else {
            $time = new Time($to_month);
            $current_time = $time->startOfMonth();

            $year = (int)$current_time->format('Y');
            $month = (int)$current_time->format('m');
        }

        $time_zone = 'UTC'; // get_option('timezone', 'UTC'); // Because statistics already save created at TZ+7
        $date1 = Time::createFromDate($year, $month, 01, $time_zone)
            ->startOfMonth()
            ->i18nFormat('yyyy-MM-dd HH:mm:ss', 'UTC', 'en');
        $date2 = Time::createFromDate($year, $month, 01, $time_zone)
            ->endOfMonth()
            ->i18nFormat('yyyy-MM-dd HH:mm:ss', 'UTC', 'en');

        $connection = ConnectionManager::get('default');

        $time_zone_offset = Time::now($time_zone)->format('P');

        $CurrentMonthDays = Cache::read('currentMonthDays_' . $auth_user_id . '_' . $date1 . '_' . $date2, '15min');
        if ($CurrentMonthDays === false) {
            $sql = "SELECT
                  CASE
                    WHEN Statistics.publisher_earn > 0
                    THEN
                      DATE_FORMAT(CONVERT_TZ(Statistics.created,'+00:00','" . $time_zone_offset . "'), '%Y-%m-%d')
                  END  AS `day`,
                  CASE
                    WHEN Statistics.publisher_earn > 0
                    THEN
                      COUNT(Statistics.id)
                  END AS `count`,
                  CASE
                    WHEN Statistics.publisher_earn > 0
                    THEN
                      SUM(Statistics.publisher_earn)
                  END AS `publisher_earnings`
                FROM
                  statistics Statistics
                WHERE
                  (
                    Statistics.created BETWEEN :date1 AND :date2
                    AND Statistics.user_id = {$auth_user_id}
                  )
                GROUP BY
                  day";

            $stmt = $connection->prepare($sql);
            $stmt->bindValue('date1', $date1, 'datetime');
            $stmt->bindValue('date2', $date2, 'datetime');
            $stmt->execute();
            $views_publisher = $stmt->fetchAll('assoc');

            $sql = "SELECT
                  CASE
                    WHEN Statistics.referral_earn > 0
                    THEN
                      DATE_FORMAT(CONVERT_TZ(Statistics.created,'+00:00','" . $time_zone_offset . "'), '%Y-%m-%d')
                  END  AS `day`,
                  CASE
                    WHEN Statistics.referral_earn > 0
                    THEN
                      SUM(Statistics.referral_earn)
                  END AS `referral_earnings`
                FROM
                  statistics Statistics
                WHERE
                  (
                    Statistics.created BETWEEN :date1 AND :date2
                    AND Statistics.referral_id = {$auth_user_id}
                  )
                GROUP BY
                  day";

            $stmt = $connection->prepare($sql);
            $stmt->bindValue('date1', $date1, 'datetime');
            $stmt->bindValue('date2', $date2, 'datetime');
            $stmt->execute();
            $views_referral = $stmt->fetchAll('assoc');

            $CurrentMonthDays = [];

            $targetTime = Time::createFromDate($year, $month, 01)->startOfMonth();

            for ($i = 1; $i <= $targetTime->format('t'); $i++) {
                $CurrentMonthDays[$year . "-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-" .
                    str_pad($i, 2, '0', STR_PAD_LEFT)] = [
                    'view' => 0,
                    'publisher_earnings' => 0,
                    'referral_earnings' => 0,
                ];
            }

            foreach ($views_publisher as $view) {
                if (!$view['day']) {
                    continue;
                }

                $day = $view['day'];
                $CurrentMonthDays[$day]['view'] = $view['count'];
                $CurrentMonthDays[$day]['publisher_earnings'] = $view['publisher_earnings'];
            }
            unset($view);
            foreach ($views_referral as $view) {
                if (!$view['day']) {
                    continue;
                }

                $day = $view['day'];
                $CurrentMonthDays[$day]['referral_earnings'] = $view['referral_earnings'];
            }
            unset($view);
            dd($CurrentMonthDays);

            if ((bool)get_option('cache_member_statistics', 1)) {
                Cache::write(
                    'currentMonthDays_' . $auth_user_id . '_' . $date1 . '_' . $date2,
                    $CurrentMonthDays,
                    '15min'
                );
            }
        }

        //new
        /*
        if ($CurrentMonthDays === false) {
            $sql = "SELECT
                        DATE_FORMAT( CONVERT_TZ( Statistics.created, '+00:00', '" . $time_zone_offset . "' ), '%Y-%m-%d' ) AS `day`,
                        COUNT( Statistics.id ) AS count,
                        sum( CASE WHEN Statistics.country = 'VN' THEN 1 ELSE 0 END ) AS count_vn,
                        sum( Statistics.publisher_earn ) AS publisher_earnings,
                        sum( Statistics.referral_earn ) AS referral_earnings 
                    FROM
                        statistics Statistics 
                    WHERE
                        Statistics.created BETWEEN :date1 AND :date2
                        AND Statistics.user_id = {$auth_user_id}
                        AND Statistics.publisher_earn > 0 
                    GROUP BY
                        `day`
            ";
            $stmt = $connection->prepare($sql);
            $stmt->bindValue('date1', $date1, 'datetime');
            $stmt->bindValue('date2', $date2, 'datetime');
            $stmt->execute();
            $dataDate = $stmt->fetchAll('assoc');

            $CurrentMonthDays = [];

            $targetTime = Time::createFromDate($year, $month, 01)->startOfMonth();

            for ($i = 1; $i <= $targetTime->format('t'); $i++) {
                $CurrentMonthDays[$year . "-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-" .
                str_pad($i, 2, '0', STR_PAD_LEFT)] = [
                    'view_vn' => 0,
                    'view' => 0,
                    'publisher_earnings' => 0,
                    'referral_earnings' => 0,
                ];
            }

            if (!empty($dataDate) && is_array($dataDate) && count($dataDate)){
                foreach ($dataDate as $view){
                    if (!$view['day']) continue;
                    $day = $view['day'];
                    $CurrentMonthDays[$day]['view'] = $view['count'];
                    $CurrentMonthDays[$day]['view_vn'] = $view['count_vn'];
                    $CurrentMonthDays[$day]['publisher_earnings'] = $view['publisher_earnings'];
                    $CurrentMonthDays[$day]['referral_earnings'] = $view['referral_earnings'];
                }
            }
            unset($view);

            if ((bool)get_option('cache_member_statistics', 1)) {
                Cache::write(
                    'currentMonthDays_' . $auth_user_id . '_' . $date1 . '_' . $date2,
                    $CurrentMonthDays,
                    '15min'
                );
            }
        }
        */
        $this->set('CurrentMonthDays', $CurrentMonthDays);

        $this->set('total_views', array_sum(array_column_polyfill($CurrentMonthDays, 'view')));
        $this->set('total_earnings', array_sum(array_column_polyfill($CurrentMonthDays, 'publisher_earnings')));
        $this->set('referral_earnings', array_sum(array_column_polyfill($CurrentMonthDays, 'referral_earnings')));

        $this->loadModel('Announcements');

        $announcements = $this->Announcements->find()
            ->where(['Announcements.published' => 1])
            ->order(['Announcements.id DESC'])
            ->limit(3)
            ->toArray();
        $this->set('announcements', $announcements);
        $this->set('traffics_statics', $this->staticsTraffics());
    }

    public function staticsTraffics($value = '')
    {
        /**
         * @var \App\Model\Entity\Traffic $traffic
         * @var \App\Model\Entity\Datetf $datetf
//             * @var \App\Model\Entity\Jobtf $jobtf
         */
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $this->loadModel('Traffics');
        $this->loadModel('Datetfs');
        $this->loadModel('Jobtfs');
        $query = $this->Traffics->find()
            ->contain(['Datetfs'])
            ->toArray();
        $traffics = array();
        $traffics['views_day'] = 0;
        $traffics['count'] = 0;
        $traffics['count_day'] = 0;
        $traffics['status'] = 0;
        $traffics['views'] = 0;
        $traffics['all'] = 0;
        $traffics['date'] = 0;
        $traffics['device'] = 0;
        foreach ($query as $value) {
            $traffics['count'] += $value->count;
            if ($value->status == 1) {
                $traffics['count_day'] += $value->count_day;
                $traffics['status'] += 1;
            }
            $traffics['views'] += $value->views;
            $traffics['all'] += 1;
        }
        $datetfs = $this->Datetfs->find()
            ->where([
                'date' => date('Y-m-d')
            ])
            ->toArray();
        foreach ($datetfs as $value) {
            $traffics['date'] += $value->views;
        }
        return $traffics;
    }


    public function referrals()
    {
        if ((bool)get_option('enable_referrals', 1) === false) {
            throw new NotFoundException(__('Invalid request'));
        }
        $query = $this->Users->find()
            ->where(['referred_by' => $this->Auth->user('id')]);
        $referrals = $this->paginate($query);

        $this->set('referrals', $referrals);
    }

    public function profile()
    {
        $user = $this->Users->find()->contain(['Plans'])->where(['Users.id' => $this->Auth->user('id')])->first();

        if ($this->getRequest()->is(['post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->getRequest()->data);
            //debug($user->errors());
            if ($this->Users->save($user)) {
                if ($this->Auth->user('id') === $user->id) {
                    // Fetch full user data with password hash to update session
                    $fullUser = $this->Users->find()
                        ->contain(['Plans'])
                        ->where(['Users.id' => $user->id])
                        ->first();

                    if ($fullUser) {
                        $data = $fullUser->toArray();
                        $this->Auth->setUser($data);
                    }
                }
                $this->Flash->success(__('Profile has been updated'));
                return $this->redirect(['action' => 'profile']);
            } else {
                $this->Flash->error(__('Oops! There are mistakes in the form. Please make the correction.'));
            }
        }
        unset($user->password);
        $this->set('user', $user);
    }

    public function plans()
    {
        if ((bool)get_option('enable_premium_membership') === false) {
            throw new NotFoundException(__('404 Not Found'));
        }

        $user = $this->Users->findById($this->Auth->user('id'))->contain(['Plans'])->first();
        $this->set('user', $user);

        $plans = $this->Users->plans->find()->where(['enable' => 1, 'hidden' => 0]);
        $this->set('plans', $plans);
    }

    public function payPlan($id = null, $period = null)
    {
        if ((bool)get_option('enable_premium_membership') === false) {
            throw new NotFoundException(__('404 Not Found'));
        }

        $this->getRequest()->allowMethod(['post']);

        if (!$id || !$period) {
            throw new NotFoundException(__('Invalid request'));
        }

        $plan = $this->Users->Plans->findById($id)->first();

        $amount = $plan->yearly_price;
        $period_name = __("Yearly");
        if ($period === 'm') {
            $amount = $plan->monthly_price;
            $period_name = __("Monthly");
        }

        $data = [
            'status' => 2, //Unpaid Invoice
            'user_id' => $this->Auth->user('id'),
            'description' => __("{0} Premium Membership: {1}", [$period_name, $plan->title]),
            'type' => 1, //Plan Invoice
            'rel_id' => $plan->id, //Plan Id
            'payment_method' => '',
            'amount' => price_database_format($amount),
            'data' => serialize([
                'payment_period' => $period,
            ]),
        ];

        $invoice = $this->Users->Invoices->newEntity($data);

        if ($this->Users->Invoices->save($invoice)) {
            if ((bool)get_option('alert_admin_created_invoice', 0)) {
                try {
                    $this->getMailer('Notification')->send('newInvoice', [$invoice, $this->logged_user]);
                } catch (\Exception $exception) {
                    \Cake\Log\Log::write('error', $exception->getMessage());
                }
            }

            $this->Flash->success(__('An invoice with id: {0} has been generated.', $invoice->id));

            return $this->redirect(['controller' => 'Invoices', 'action' => 'view', $invoice->id]);
        }
    }

    public function changeEmail($username = null, $key = null)
    {
        if (!$username && !$key) {
            $user = $this->Users->findById($this->Auth->user('id'))->first();

            if ($this->getRequest()->is(['post', 'put'])) {
                $uuid = \Cake\Utility\Text::uuid();

                $user->activation_key = \Cake\Utility\Security::hash($uuid, 'sha1', true);

                $user = $this->Users->patchEntity($user, $this->getRequest()->data, ['validate' => 'changEemail']);

                if ($this->Users->save($user)) {
                    // Send rest email
                    try {
                        $this->getMailer('User')->send('changeEmail', [$user]);
                    } catch (\Exception $exception) {
                        \Cake\Log\Log::write('error', $exception->getMessage());
                    }

                    $this->Flash->success(__('Kindly check your email to confirm it.'));

                    $this->redirect(['action' => 'changeEmail']);
                } else {
                    $this->Flash->error(__('Oops! There are mistakes in the form. Please make the correction.'));
                }
            }
            $this->set('user', $user);
        } else {
            $user = $this->Users->find('all')
                ->contain(['Plans'])
                ->where([
                    'Users.status' => 1,
                    'Users.username' => $username,
                    'Users.activation_key' => $key,
                ])
                ->first();

            if (!$user) {
                $this->Flash->error(__('Invalid Activation.'));

                return $this->redirect(['action' => 'changeEmail']);
            }

            $user->email = $user->temp_email;
            $user->temp_email = '';
            $user->activation_key = '';

            if ($this->Users->save($user)) {
                if ($this->Auth->user('id') === $user->id) {
                    $data = $user->toArray();
                    unset($data['password']);

                    $this->Auth->setUser($data);
                }
                $this->Flash->success(__('Your email has been confirmed.'));

                return $this->redirect(['action' => 'signin', 'prefix' => 'auth']);
            } else {
                $this->Flash->error(__('Unable to confirm your email.'));

                return $this->redirect(['action' => 'changeEmail']);
            }
        }
    }

    public function changePassword()
    {
        $user = $this->Users->findById($this->Auth->user('id'))->first();

        if ($this->getRequest()->is(['post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->getRequest()->data, ['validate' => 'changePassword']);
            //debug($user->errors());
            if ($this->Users->save($user)) {
                $this->Users->RememberTokens->deleteAll(['user_id' => $user->id]);

                $this->Flash->success(__('Password has been updated'));

                $this->redirect(['action' => 'changePassword']);
            } else {
                $this->Flash->error(__('Oops! There are mistakes in the form. Please make the correction.'));
            }
        }
        unset($user->password);
        $this->set('user', $user);
    }
}
