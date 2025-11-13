<?php



namespace App\Controller\Admin;



use App\Model\Entity\BuyerInvoice;
use App\Model\Table\BuyerInvoicesTable;
use Cake\Event\Event;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\Time;

/**
 * @property \App\Model\Table\OptionsTable $Options
 */

class Traffics2Controller extends AppAdminController{

    public $table = 'options';

    public function beforeFilter(Event $event){
        parent::beforeFilter($event);
        $this->loadModel('Options');

    }

    public function payoutBanner()
    {
        $option = $this->Options->findByName('payout_rates_banner_traffics2')->first();
        if (!$option) {
            throw new NotFoundException(__('Invalid option'));
        }

        $option->value = unserialize($option->value);

        if ($this->getRequest()->is(['post', 'put'])) {
            foreach ($this->getRequest()->getData('value') as $key => $value) {
                if (!empty($value[2]) && !empty($value[3])) {
                    $option->value[$key] = [
                        2 => abs($value[2]),
                        3 => abs($value[3]),
                    ];
                } else {
                    $option->value[$key] = [
                        2 => '',
                        3 => '',
                    ];
                }
            }
            unset($key, $value);

            $option->value = serialize($option->value);

            if ($this->Options->save($option)) {
                //debug($option);
                $this->Flash->success('Prices have been updated.');

                return $this->redirect(['action' => 'payoutBanner']);
            } else {
                $this->Flash->error(__('Oops! There are mistakes in the form. Please make the correction.'));
            }
        }

        $this->set('option', $option);
    }

    public function settings(){
        $traffics_settings = get_option('traffic_default_time',60);
        $traffics2_settings = get_option('traffic2_default_time',80);
        $traffics2_url_settings = get_option('traffic2_url_default_time',10);

        if ($this->getRequest()->is(['post','put'])){
            $options = $this->getRequest()->getData();
            foreach ($options as $k => $item){
                $option = $this->Options->find()->where(['name' => $k])->first();
                if (!$option) $option = $this->Options->newEntity();
                $option->name = $k;
                $option->value = $this->getRequest()->getData($k);
                $this->Options->save($option);
            }
            $this->Flash->success('Save Success');
            return $this->redirect(['action' => 'Settings']);
        }
        $this->set('traffics_settings',$traffics_settings);
        $this->set('traffics2_settings',$traffics2_settings);
        $this->set('traffics2_url_settings',$traffics2_url_settings);
    }

}

