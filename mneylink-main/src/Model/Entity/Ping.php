<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property string $ip
 * @property string $alias
 * @property int $online
 * @property \Cake\I18n\FrozenTime|null $date
 */
class Ping extends Entity{
}
