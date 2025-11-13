<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Text;
/**
 * @property \App\Model\Table\TrafficsTable&\Cake\ORM\Association\BelongsTo $Traffics
 * @method \Cake\ORM\Query findById($id)
 * @method \Cake\ORM\Query findByAlias($alias)
 * @method \App\Model\Entity\Datetf get($primaryKey, $options = [])
 * @method \App\Model\Entity\Datetf newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Datetf[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Datetf|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Datetf saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Datetf patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Datetf[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Datetf findOrCreate($search, callable $callback = null, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @method \App\Model\Entity\Datetf[]|\Cake\Datasource\ResultSetInterface|false saveMany($entities, $options = [])
 * @method \App\Model\Entity\Datetf[]|\Cake\Datasource\ResultSetInterface saveManyOrFail($entities, $options = [])
 * @method \App\Model\Entity\Datetf[]|\Cake\Datasource\ResultSetInterface|false deleteMany($entities, $options = [])
 * @method \App\Model\Entity\Datetf[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail($entities, $options = [])
 */

class DatetfsTable extends Table
{
    public function initialize(array $config)
    {
        $this->belongsTo('Traffics');
        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->notBlank('date', __('Choose a valid value.'))
            ->numeric('views', __('Choose a valid value.'))
            ->numeric('traffic_id', __('Choose a valid value.'));

        return $validator;
    }

    public function totalView($user,$today = false){
        $condition = ['user_id' => $user];
        if ($today) $condition = array_merge($condition,['date' => date('Y-m-d')]);
        return $this->find()->where($condition)->sumOf('views');
    }
}
