<?php



namespace App\Controller\Admin;



use App\Model\Entity\BuyerInvoice;
use App\Model\Table\BuyerInvoicesTable;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\Time;


/**

 * @property \App\Model\Table\TrafficsTable $Traffics

 * @property \App\Model\Table\JobtfsTable $Jobtfs
 * @property BuyerInvoicesTable $BuyerInvoices

 */

class TrafficsController extends AppAdminController

{
    

	public $paginate = [
        'limit' => 10
    ];

    public function index()
    {
        ini_set('memory_limit', '-1');
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        /**
         * @var \App\Model\Entity\Traffic $traffic
         */

        $query = $this->Traffics->find()->contain(['Users'])->where(['Traffics.id >=' => 0]);
        $filter = $this->getRequest()->getQuery('Filter');
        if (!empty($filter['id'])) $query->where(['Traffics.id' => $filter['id']]);
        if (!empty($filter['name'])) $query->where(['Traffics.title LIKE' => "%{$filter['name']}%"]);
        if (!empty($filter['url'])) $query->where(['Traffics.url LIKE' => "%{$filter['url']}%"]);
        if (!empty($filter['status']) && strlen($filter['status']) > 0) $query->where(['Traffics.status' => $filter['status']]);
        if (!empty($filter['user'])){
            $this->loadModel('Users');
            $users = $this->Users->find()->where(['username LIKE' => "%{$filter['user']}%"])->toArray();
            $ids = [];
            if (!empty($users)){
                foreach ($users as $user){
                    $ids[] = $user->id;
                }
            }
            if (!empty($ids) && count($ids)) $query->where(['user_id IN' => $ids]);
        }

        $traffics = $this->paginate($query);

        $this->set('traffics', $traffics);
        $this->set('traffics_statics', $this->staticsTraffics());
    }

    public function staticsTraffics($value='')
    {
        /**
             * @var \App\Model\Entity\Traffic $traffic
             * @var \App\Model\Entity\Datetf $datetf
//             * @var \App\Model\Entity\Jobtf $jobtf
        */
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
        $traffics['date']= 0;
        $traffics['device']= 0;
        foreach ($query as $value) {
            $traffics['count'] += $value->count; 
            if($value->status == 1) {
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
        /*$traffics['device'] = $this->Jobtfs->find()
                    ->where([
                        'status' => 1,
                        'device' => 0
                    ])
                    ->count(); */
        return $traffics;

    }



    public function add()

    {

        /**

         * @var \App\Model\Entity\Traffic $traffic

         */

        $traffic = $this->Traffics->newEntity();



        if ($this->getRequest()->is('post')) {

            $traffic = $this->Traffics->patchEntity($traffic, $this->getRequest()->getData());
            $traffic->except_region = json_encode($this->getRequest()->getData('except_region'));
            $traffic->only_region = json_encode($this->getRequest()->getData('only_region'));
            $traffic->traffic_ver2_url = json_encode($this->getRequest()->getData('traffic_ver2_url'));
            $traffic->device = $this->getRequest()->getData('device');



            // $user->api_token = \Cake\Utility\Security::hash(\Cake\Utility\Text::uuid(), 'sha1', true);



            if ($this->Traffics->save($traffic)) {

                $this->Flash->success(__('Thêm chiến dịch thành công'));



                return $this->redirect(['action' => 'edit', $traffic->id]);

            }

            $this->Flash->error(__('Không thể thêm chiến dịch. Hãy thử lại!'));

        }

        $this->set('randomString', $this->randomString());

    }



    public function randomString($length = 10)

    {

        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $charactersLength = strlen($characters);

        $randomString = '';

        for ($i = 0; $i < $length; $i++) {

            $randomString .= $characters[rand(0, $charactersLength - 1)];

        }

        return $randomString;

    }



   public function edit($id = null)

    {
        if (!$id) {throw new NotFoundException(__('Invalid campaign'));}

        $traffic = $this->Traffics->get($id);

        if (!$traffic) {throw new NotFoundException(__('Invalid Post'));}

        if ($this->getRequest()->is(['post', 'put'])) {
            $traffic = $this->Traffics->patchEntity($traffic, $this->getRequest()->getData());
            $traffic->except_region = json_encode($this->getRequest()->getData('except_region'));
            $traffic->only_region = json_encode($this->getRequest()->getData('only_region'));
            $traffic->traffic_ver2_url = json_encode($this->getRequest()->getData('traffic_ver2_url'));
            $traffic->device = $this->getRequest()->getData('device');

            if ($this->Traffics->save($traffic)) {
                if ($traffic->user_id) $this->syncBuyerStatus($traffic);

                $this->Flash->success(__('Cập nhật chiến dịch thành công'));

                return $this->redirect(['action' => 'edit', $traffic->id]);
            }

            $this->Flash->error(__('Oops! There are mistakes in the form. Please make the correction.'));

        }

        $this->set('traffic', $traffic);

    }



    public function view($id= null)

    {

    	if (!$id) {

            throw new NotFoundException(__('Invalid campaign'));

        }

$this->loadModel('Jobtfs');

        $traffic = $this->Traffics->findById($id)

            ->contain(['Datetfs'])

            ->contain(['Jobtfs'])

            ->first();

        if (!$traffic) {

            throw new NotFoundException(__('Invalid campaign'));

        }

        $user_id = $this->getRequest()->getQuery('user_id');

        $this->set('traffic', $traffic);

        $this->set('user_id', $user_id);



        /**

         * @var \App\Model\Entity\Jobtfs $jobtfs

         */

            $jobtfs = $this->Jobtfs->find()

                ->select([

                    'user_id',

                    'status',

                    'status_count' => 'COUNT(status)',

                ])

                ->where(['traffic_id' => $id])

                ->order(['status_count' => 'DESC'])

                ->group(['user_id','status'])

                ->toArray();



                $jobtf = array();

                foreach ($jobtfs as $value) {

                    $jobtf[$value->user_id][$value->status] = $value->status_count;

                }





$this->set('jobtfs', $jobtf);



    }





    public function delete($id)

    {

        // if ($this->getRequest()->getParam('_csrfToken') !== $this->getRequest()->getQuery('token')) {

        //     throw new ForbiddenException();

        // }



        if ($this->deleteLink($id)) {

            $this->Flash->success(__('Chiến dịch đã được xóa thành công'));

        }



        return $this->redirect(['action' => 'index']);

    }


public function deleteAll()

    {
        $this->getRequest()->allowMethod(['post', 'delete']);

        $this->Traffics->Jobtfs->deleteAll(['created <=' => date('Y-m-d 00:00:00')]);

        $this->Traffics->Jobtfs->deleteAll(['created <=' => date('Y-m-d H:i:s', strtotime('-20 minute', time())),
        'status' => 0]);



            $this->Flash->success(__('Dữ liệu đã được xóa thành công'));




        return $this->redirect(['action' => 'index']);

    }


    protected function deleteLink($id)

    {

        /** @var \App\Model\Entity\Traffic $traffic */

        $traffic = $this->Traffics->findById($id)->first();



        if (!$this->Traffics->delete($traffic)) {

            return false;

        }

        $this->Traffics->Jobtfs->deleteAll(['traffic_id' => $id]);

        $this->Traffics->Datetfs->deleteAll(['traffic_id' => $id]);



        return true;

    }
    
    public function checking() {
        
        $this->loadModel('Jobtfs');

$user_id = $this->getRequest()->getQuery('user_id');
        /**
         * @var \App\Model\Entity\Jobtfs $jobtfs
         */
         
         

            $query = $this->Jobtfs->find()
                ->where(['status' => 1,'user_id' => $user_id])->order(['id' => 'DESC']);;
                
                $jobtfs = $this->paginate($query);
                
        $this->set('jobtfs', $jobtfs);
        $this->set('user_id', $user_id);
    }

    public function syncBuyerStatus($traffic){
        /**
         * @var BuyerInvoice $buyerInvoice
         */
        $this->loadModel('BuyerInvoices');
        $this->loadModel('Users');
        $user = $this->Users->get($traffic->user_id);
        if (in_array($user->role,['admin','buyer'])){
            $buyerInvoice = $this->BuyerInvoices->get($traffic->invoice_id);
            //Đang chạy
            if ($traffic->status == 1){
                $buyerInvoice->status = 1;
                $buyerInvoice->date_payment = Time::now('Asia/Ho_Chi_Minh');
            }
            $this->BuyerInvoices->save($buyerInvoice);
        }
    }

    public function bulkEdit(){
        $ids = $this->getRequest()->getData('ids');
        $action = $this->getRequest()->getData('action');
        if ($action == 'delete'){
            if (!empty($ids)){
                foreach ($ids as $id){
                    $traffic = $this->Traffics->get($id);
                    $delete = $this->Traffics->delete($traffic);
                }
                return $this->redirect(['controller' => 'traffics','action' => 'index']);
            }
        }
        if ($action == 'active'){
            if (!empty($ids)){
                foreach ($ids as $id){
                    $traffic = $this->Traffics->find()->where(['id' => $id])->first();
                    $traffic->status = 1;
                    $traffic = $this->Traffics->save($traffic);
                }
                return $this->redirect(['controller' => 'traffics','action' => 'index']);
            }
        }
        if ($action == 'pending'){
            if (!empty($ids)){
                foreach ($ids as $id){
                    $traffic = $this->Traffics->find()->where(['id' => $id])->first();
                    $traffic->status = 0;
                    $traffic = $this->Traffics->save($traffic);
                }
                return $this->redirect(['controller' => 'traffics','action' => 'index']);
            }
        }
    }
}

