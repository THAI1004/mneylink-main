<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Text;

/**
 * @property \App\Model\Table\DatetfsTable&\Cake\ORM\Association\HasMany $Datetfs
 * @property \App\Model\Table\JobtfsTable&\Cake\ORM\Association\HasMany $Jobtfs
 * @property \App\Model\Table\BuyerCampaignsTable&\Cake\ORM\Association\BelongsTo $BuyerCampaigns
 * @method \Cake\ORM\Query findById($id)
 * @method \Cake\ORM\Query findByAlias($alias)
 * @method \App\Model\Entity\Traffic get($primaryKey, $options = [])
* @method \App\Model\Entity\Traffic newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Traffic[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Traffic|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Traffic saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Traffic patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Traffic[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Traffic findOrCreate($search, callable $callback = null, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @method \App\Model\Entity\Traffic[]|\Cake\Datasource\ResultSetInterface|false saveMany($entities, $options = [])
 * @method \App\Model\Entity\Traffic[]|\Cake\Datasource\ResultSetInterface saveManyOrFail($entities, $options = [])
 * @method \App\Model\Entity\Traffic[]|\Cake\Datasource\ResultSetInterface|false deleteMany($entities, $options = [])
 * @method \App\Model\Entity\Traffic[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail($entities, $options = [])
 */
class TrafficsTable extends Table
{
    public function initialize(array $config)
    {
        
        $this->hasMany('Datetfs');
        $this->hasMany('Jobtfs');
        $this->hasOne('BuyerCampaigns')->setForeignKey('id')->setBindingKey('buyer_id');
        $this->belongsTo('BuyerInvoices')->setForeignKey('invoice_id');
        $this->belongsTo('Users')->setForeignKey('user_id');
        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->notBlank('title', __('Choose a valid value.'))
            ->notBlank('kind', __('Choose a valid value.'))
            ->notBlank('img_mobile', __('Choose a valid value.'))
            ->notBlank('img_desktop', __('Choose a valid value.'))
            ->notBlank('keywords', __('Choose a valid value.'))
            ->numeric('count_day', __('Choose a valid value.'))
            ->numeric('count', __('Choose a valid value.'))
            ->allowEmptyString('content')
            ->notBlank('url', __('Choose a valid value.'))
            ->notBlank('status', 'inList', [
                'rule' => ['inList', ['0', '1', '2', '3']],
                'message' => __('Choose a valid value.'),
            ]);

        return $validator;
    }

}
