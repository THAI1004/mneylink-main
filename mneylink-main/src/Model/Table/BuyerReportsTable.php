<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Text;

/**
 * @method \Cake\ORM\Query findById($id)
 * @method \Cake\ORM\Query findByAlias($alias)
 * @method \App\Model\Entity\BuyerInvoice get($primaryKey, $options = [])
* @method \App\Model\Entity\BuyerInvoice newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\BuyerInvoice[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\BuyerInvoice|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\BuyerInvoice saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\BuyerInvoice patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\BuyerInvoice[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\BuyerInvoice findOrCreate($search, callable $callback = null, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @method \App\Model\Entity\BuyerInvoice[]|\Cake\Datasource\ResultSetInterface|false saveMany($entities, $options = [])
 * @method \App\Model\Entity\BuyerInvoice[]|\Cake\Datasource\ResultSetInterface saveManyOrFail($entities, $options = [])
 * @method \App\Model\Entity\BuyerInvoice[]|\Cake\Datasource\ResultSetInterface|false deleteMany($entities, $options = [])
 * @method \App\Model\Entity\BuyerInvoice[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail($entities, $options = [])
 */
class BuyerReportsTable extends Table
{
    public function initialize(array $config)
    {
        $this->belongsTo('traffics')->setPrimaryKey('id');
        $this->belongsTo('users')->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
    }

}
