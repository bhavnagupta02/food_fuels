<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UserStat Entity.
 */
class UserStat extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'user_id' => true,
        'client_id' => true,
        'stat_date' => true,
        'weight' => true,
        'height' => true,
        'neck' => true,
        'chest' => true,
        'upperarm' => true,
        'forearm' => true,
        'hip' => true,
        'thigh' => true,
        'calf' => true,
        'heart_rate' => true,
        'bmi' => true,
        'bmr' => true,
        'ideal_body_weight' => true,
        'max_heart_rate' => true,
        'body_fat' => true,
        'lean_muscle_mass' => true,
        'front_image' => true,
        'side_image' => true,
        'back_image' => true,
        'user' => true,
    ];
}
