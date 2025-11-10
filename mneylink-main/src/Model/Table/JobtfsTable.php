<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Text;
use GeoIp2\Database\Reader;

/**
 * @property \App\Model\Table\TrafficsTable&\Cake\ORM\Association\BelongsTo $Traffics
 * @method \Cake\ORM\Query findById($id)
 * @method \Cake\ORM\Query findByAlias($alias)
 * @method \App\Model\Entity\Jobtf get($primaryKey, $options = [])
 * @method \App\Model\Entity\Jobtf newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Jobtf[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Jobtf|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Jobtf saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Jobtf patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Jobtf[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Jobtf findOrCreate($search, callable $callback = null, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @method \App\Model\Entity\Jobtf[]|\Cake\Datasource\ResultSetInterface|false saveMany($entities, $options = [])
 * @method \App\Model\Entity\Jobtf[]|\Cake\Datasource\ResultSetInterface saveManyOrFail($entities, $options = [])
 * @method \App\Model\Entity\Jobtf[]|\Cake\Datasource\ResultSetInterface|false deleteMany($entities, $options = [])
 * @method \App\Model\Entity\Jobtf[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail($entities, $options = [])
 */

class JobtfsTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
        $this->belongsTo('Traffics');
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->notBlank('ip', __('Choose a valid value.'))
            ->numeric('traffic_id', __('Choose a valid value.'))
            ->numeric('device', __('Choose a valid value.'))
            ->notBlank('confirm_key', __('Choose a valid value.'))
            ->add('status', 'inList', [
                'rule' => ['inList', ['0', '1', '2', '3', '4']],
                'message' => __('Choose a valid value.'),
            ]);

        return $validator;
    }
    public function get_country($ip)
    {
        if (!empty($_SERVER["HTTP_CF_IPCOUNTRY"])) {
            if (!in_array($_SERVER["HTTP_CF_IPCOUNTRY"], ['XX', 'T1'])) {
                return $_SERVER["HTTP_CF_IPCOUNTRY"];
            }
        }

        try {
            $reader = new Reader(CONFIG . 'binary/geoip/GeoLite2-Country.mmdb');
            $record = $reader->country($ip);
            $countryCode = (trim($record->country->isoCode)) ? $record->country->isoCode : 'Others';
        } catch (\Exception $ex) {
            $countryCode = 'Others';
        }

        return $countryCode;
    }
}
