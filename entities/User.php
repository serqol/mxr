<?php

namespace Entities;

use Framework\ORM\Entities\BasicEntity;

/**
 * Class User
 * @table user
 */
class User extends BasicEntity {

    /**
     * @type VARCHAR(20)
     */
    public $_name;

    /**
     * @type VARCHAR(20)
     */
    public $_email;
}