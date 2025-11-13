<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property int $traffic_id
 * @property int $views
 * @property string $date
 * @property \App\Model\Entity\Traffic $traffic
 * @property \Cake\ORM\Entity[] $_i18n
 */
class Datetf extends Entity
{
}