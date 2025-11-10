<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property string $title
 * @property string $img_mobile
 * @property string $img_desktop
 * @property string $keywords
 * @property string $url
 * @property int $count_day
 * @property int $views
 * @property int $count
 * @property int $status
 * @property string|null $content
 * @property string|null $kind
 * @property \Cake\I18n\FrozenTime $created
 * @property \App\Model\Entity\Datetfs[] $datetfs
 * @property \App\Model\Entity\Jobtfs[] $jobtfs
 * @property \Cake\ORM\Entity[] $_i18n
 * @property int $view_day
 */
class Traffic extends Entity
{
}
