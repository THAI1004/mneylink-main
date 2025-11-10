<?php

namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Cron\CronExpression;

class ScheduleCommand extends Command
{
    /**
     * php bin/cake.php schedule
     * php bin/cake.php queue runworker -q
     * bin/cake schedule
     *
     * @param Arguments $args
     * @param ConsoleIo $io
     * @return int|void|null
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $queuedJobsTable = TableRegistry::getTableLocator()->get('Queue.QueuedJobs');

        // https://github.com/dragonmantank/cron-expression#usage
        // Every minute
        $cronEveryOneMinute = new CronExpression('* * * * *');
        if ($cronEveryOneMinute->isDue()) {
            //$io->out('* * * * *');
//            $this->scheduleCronLastTimeRun($io);
        }

        // Every 5 minutes
        $cronEveryFiveMinute = new CronExpression('*/5 * * * *');
        if ($cronEveryFiveMinute->isDue()) {
            //$io->out('*/5 * * * *');
        }

        // Daily
        $cronDaily = new CronExpression('0 */1 * * *');
        if ($cronDaily->isDue()) {
//            $queuedJobsTable->createJob('DeleteLessActivityLinks', []);
//            $queuedJobsTable->createJob('DeletePendingUsers', []);
            $this->scheduleCronLastTimeRun($io);
            $this->trafficCLearViewedDay();
            // $this->clear_links();
        }

        $cronDailyHalfPart = new CronExpression('30 0 * * *');
        if ($cronDailyHalfPart->isDue()) {
            // $this->clear_report();
            // $this->clear_member_report();
        }

        $cronDailyFiveHours = new CronExpression('0 5 * * *');
        if ($cronDailyFiveHours->isDue()) {
            $this->clearJobtf();
        }

        $cronMonthTwoHours = new CronExpression('0 2 1 * *');
        if ($cronMonthTwoHours->isDue()) {
            // $this->clear_statistic();
        }

        $cronMonthThreeHours = new CronExpression('0 3 1 * *');
        if ($cronMonthThreeHours->isDue()) {
            // $this->clear_datedfs();
        }

        // Twice per day
        $cronTwicePerDay = new CronExpression('0 0,12 * * *');
        if ($cronTwicePerDay->isDue()) {
//            $queuedJobsTable->createJob('CheckPlanExpiration', []);
        }

        // Monthly
        $cronEveryOneMonth = new CronExpression('0 0 1 * *');
        if ($cronEveryOneMonth->isDue()) {
            //
        }
    }

    /**
     * @param ConsoleIo $io
     */
    protected function scheduleCronLastTimeRun($io)
    {
        $optionsTable = TableRegistry::getTableLocator()->get('Options');

        $schedule_cron_last_time_run = $optionsTable->find()->where(['name' => 'schedule_cron_last_time_run'])->first();

        if (!$schedule_cron_last_time_run) {
            return false;
        }

        $schedule_cron_last_time_run->value = Time::now()->format('Y-m-d H:i:s');
        $optionsTable->save($schedule_cron_last_time_run);

        return true;
    }

    /**
     * @param ConsoleIo $io
     */
    protected function deleteInactiveLinks($io)
    {
        $olderThanDays = 15;

        /** @var \App\Model\Table\LinksTable $linksTable */
        $linksTable = TableRegistry::getTableLocator()->get('Links');
        /** @var \App\Model\Table\LinksTable $usersTable */
        $usersTable = TableRegistry::getTableLocator()->get('Users');
        /** @var \App\Model\Table\StatisticsTable $statisticsTable */
        $statisticsTable = TableRegistry::getTableLocator()->get('Statistics');

        $sql = "SELECT DISTINCT `link_id` FROM `statistics` WHERE `created` > :date1";

        $date = Time::now()->subDays($olderThanDays)->startOfDay()->toDateTimeString();

        $connection = ConnectionManager::get('default');
        $stmt = $connection->prepare($sql);
        $stmt->bindValue('date1', $date, 'datetime');
        $stmt->execute();
        $activeLinks = $stmt->fetchAll('assoc');
        $activeLinksIds = array_column_polyfill($activeLinks, 'link_id');

        $deleteLinksQuery = $linksTable->query();
        $deleteLinksQuery->delete()
            ->whereNotInList('id', $activeLinksIds)
            ->execute();

        $io->out('Inactive Links deleted');

        $statisticsLinksQuery = $statisticsTable->query();
        $statisticsLinksQuery->delete()
            ->whereNotInList('link_id', $activeLinksIds)
            ->execute();

        $io->out('Inactive Links statistics deleted');
    }

    /**
     * @param ConsoleIo $io
     */
    protected function deleteInactiveUsers($io)
    {
        $olderThanDays = 15;

        /** @var \App\Model\Table\LinksTable $linksTable */
        $linksTable = TableRegistry::getTableLocator()->get('Links');
        /** @var \App\Model\Table\LinksTable $usersTable */
        $usersTable = TableRegistry::getTableLocator()->get('Users');
        /** @var \App\Model\Table\StatisticsTable $statisticsTable */
        $statisticsTable = TableRegistry::getTableLocator()->get('Statistics');

        $sql = "SELECT DISTINCT `user_id` FROM `statistics` WHERE `created` > :date1";

        $date = Time::now()->subDays($olderThanDays)->startOfDay()->toDateTimeString();

        $connection = ConnectionManager::get('default');
        $stmt = $connection->prepare($sql);
        $stmt->bindValue('date1', $date, 'datetime');
        $stmt->execute();
        $activeUsers = $stmt->fetchAll('assoc');
        $activeUsersIds = array_column_polyfill($activeUsers, 'user_id');

        $deleteUsersQuery = $usersTable->query();
        $deleteUsersQuery->delete()
            ->whereNotInList('id', $activeUsersIds)
            ->execute();

        $io->out('Inactive Users deleted');

        $deleteLinksQuery = $linksTable->query();
        $deleteLinksQuery->delete()
            ->whereNotInList('user_id', $activeUsersIds)
            ->execute();

        $io->out('Inactive user links deleted');

        $statisticsLinksQuery = $statisticsTable->query();
        $statisticsLinksQuery->delete()
            ->whereNotInList('user_id', $activeUsersIds)
            ->execute();

        $io->out('Inactive users statistics deleted');

        // Todo: Remove referral
    }

    public function trafficCLearViewedDay(){
        $trafficTable = $this->getTableLocator()->get('traffics');
        $traffics = $trafficTable->find()->where(['view_day >' => 0])->toArray();
        if (!empty($traffics)){
            foreach ($traffics as $traffic){
                $traffic->view_day = 0;
                $trafficTable->save($traffic);
            }
        }
    }

    public function clearJobtf(){
        $created_check = Time::now()->modify('-2 month')->startOfMonth();
        $jobTable = $this->getTableLocator()->get('jobtfs');
        $jobTable->deleteAll([
            'created <' => $created_check
        ]);
    }

    public function clear_report(){
        $reportTable = $this->getTableLocator()->get('buyer_reports');
        $reportTable->deleteAll([
            'status' => 1,
        ]);
    }
    public function clear_member_report(){
        $reportTable = $this->getTableLocator()->get('member_reports');
        $reportTable->deleteAll(['status' => 0]);
    }

    public function clear_statistic(){
        $statistics = $this->getTableLocator()->get('statistics');
        $date_checked = Time::now()->modify('-2 month')->startOfMonth()->format('Y-m-d');
        $statistics->deleteAll(['created <' => $date_checked]);
    }

    public function clear_datedfs(){
        $datetfs = $this->getTableLocator()->get('datetfs');
        $date_checked = Time::now()->modify('-2 month')->startOfMonth()->format('Y-m-d');
        $datetfs->deleteAll(['date <' => $date_checked]);
    }

    public function clear_links(){
        $links = $this->getTableLocator()->get('links');
        $links->deleteAll([
            'user_id' => 1
        ]);
    }
}
