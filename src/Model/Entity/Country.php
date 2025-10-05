<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Country Entity.
 */
class Country extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'country' => true,
        'country_code' => true,
        'country_code_iso3' => true,
        'is_publish' => true,
        'users' => true,
    ];
}
