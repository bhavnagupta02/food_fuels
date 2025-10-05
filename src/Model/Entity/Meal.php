<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Meal Entity.
 */
class Meal extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
    ];
}
