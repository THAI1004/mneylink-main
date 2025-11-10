<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Text;

/**
 * @method \Cake\ORM\Query findById($id)
 * @method \Cake\ORM\Query findByAlias($alias)
 * @method \App\Model\Entity\MemberReport get($primaryKey, $options = [])
* @method \App\Model\Entity\MemberReport newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\MemberReport[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MemberReport|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MemberReport saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MemberReport patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MemberReport[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\MemberReport findOrCreate($search, callable $callback = null, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @method \App\Model\Entity\MemberReport[]|\Cake\Datasource\ResultSetInterface|false saveMany($entities, $options = [])
 * @method \App\Model\Entity\MemberReport[]|\Cake\Datasource\ResultSetInterface saveManyOrFail($entities, $options = [])
 * @method \App\Model\Entity\MemberReport[]|\Cake\Datasource\ResultSetInterface|false deleteMany($entities, $options = [])
 * @method \App\Model\Entity\MemberReport[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail($entities, $options = [])
 */
class MemberReportsTable extends Table
{
    public function initialize(array $config)
    {
        $this->belongsTo('traffics')->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
    }

}
