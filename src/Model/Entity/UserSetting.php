<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UserSetting Entity.
 */
class UserSetting extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'user_id' => true,
        'weight_sett' => true,
        'height_sett' => true,
        'distance_sett' => true,
        'energy_sett' => true,
        'currency_sett' => true,
        'date_sett' => true,
        'time_sett' => true,
        'user' => true,
    ];
}
