<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property int $traffic_id
 * @property int $device
 * @property string $ip
 * @property string $confirm_key
 * @property string $link_url
 * @property int $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \App\Model\Entity\Traffic $traffic
 */
class Jobtf extends Entity
{
}