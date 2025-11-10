<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Text;

/**
 * @method \Cake\ORM\Query findById($id)
 * @method \Cake\ORM\Query findByAlias($alias)
 * @method \App\Model\Entity\BuyerCampaign get($primaryKey, $options = [])
* @method \App\Model\Entity\BuyerCampaign newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\BuyerCampaign[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\BuyerCampaign|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\BuyerCampaign saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\BuyerCampaign patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\BuyerCampaign[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\BuyerCampaign findOrCreate($search, callable $callback = null, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @method \App\Model\Entity\BuyerCampaign[]|\Cake\Datasource\ResultSetInterface|false saveMany($entities, $options = [])
 * @method \App\Model\Entity\BuyerCampaign[]|\Cake\Datasource\ResultSetInterface saveManyOrFail($entities, $options = [])
 * @method \App\Model\Entity\BuyerCampaign[]|\Cake\Datasource\ResultSetInterface|false deleteMany($entities, $options = [])
 * @method \App\Model\Entity\BuyerCampaign[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail($entities, $options = [])
 */
class BuyerCampaignsTable extends Table
{
    public function initialize(array $config)
    {
        $this->hasMany('Datetfs');
        $this->hasMany('Jobtfs');
        $this->addBehavior('Timestamp');
    }

}
