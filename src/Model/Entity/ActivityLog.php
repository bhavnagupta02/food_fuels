<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ActivityLog Entity.
 */
class ActivityLog extends Entity
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
