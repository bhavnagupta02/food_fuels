<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UserSubscription Entity.
 */
class UserSubscription extends Entity
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
