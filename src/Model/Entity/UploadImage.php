<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UploadImage Entity.
 */
class UploadImage extends Entity
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
