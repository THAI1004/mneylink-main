<?php

namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\I18n\Time;

class ToolsCommand extends Command
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
    protected function buildOptionParser(ConsoleOptionParser $parser){
        $parser->addArgument('action', [
            'help' => 'Action Clear'
        ]);
        return $parser;
    }

    public function execute(Arguments $args, ConsoleIo $io){
        $action = $args->getArgument('action');
        switch ($action){
            case 'clear_statistic' : $this->clear_statistic($io); break;
            case 'clear_jobtfs' : $this->clearJobtf($io); break;
            case 'clear_report' : $this->clear_report($io); break;
            case 'clear_member_report' : $this->clear_member_report($io); break;
            case 'clear_datedfs' : $this->clear_datedfs($io); break;
            case 'clear_links' : $this->clear_links($io); break;
	    case 'clear_viewed': $this->trafficCLearViewedDay(); break;
            default: $io->out('Not action');
        }
        $name = $args->getArgument('name');
    }


    public function clearJobtf($io){
        $created_check = Time::now()->startOfDay();
        $jobTable = $this->getTableLocator()->get('jobtfs');
        if ($jobTable->deleteAll(['created <' => $created_check])){
            $io->out('Delete success');
        } else {
            $io->out('Not item delete');
        }
    }

    public function clear_report($io){
        $reportTable = $this->getTableLocator()->get('buyer_reports');
        if ($reportTable->deleteAll(['status' => 1])){
            $io->out('Delete success');
        } else {
            $io->out('Not item delete');
        }
    }
    public function clear_member_report($io){
        $reportTable = $this->getTableLocator()->get('member_reports');
        if ($reportTable->deleteAll(['status' => 0])){
            $io->out('Delete success');
        } else {
            $io->out('Not item delete');
        }
    }

    public function clear_statistic($io){
        $statistics = $this->getTableLocator()->get('statistics');
        $date_checked = Time::now()->modify('-1 month')->startOfMonth()->format('Y-m-d');
        if ($statistics->deleteAll(['created <' => $date_checked])){
            $io->out('Delete success');
        } else {
            $io->out('Not item delete');
        }
    }

    public function clear_datedfs($io){
        $datetfs = $this->getTableLocator()->get('datetfs');
        $date_checked = Time::now()->modify('-1 month')->startOfMonth()->format('Y-m-d');
        if ($datetfs->deleteAll(['date <' => $date_checked])){
            $io->out('Delete success');
        } else {
            $io->out('Not item delete');
        }
    }

    public function clear_links($io){
        $links = $this->getTableLocator()->get('links');
        if ($links->deleteAll(['user_id' => 1])){
            $io->out('Delete success');
        } else {
            $io->out('Not item delete');
        }
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
}
