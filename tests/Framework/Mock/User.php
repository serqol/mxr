<?php

namespace Tests\Framework\Mock;
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

    /**
     * @param $param1
     * @param $param2
     * @param $param3
     * @return int
     */
    public function getSanity($param1, $param2, $param3) {
        return 0;
    }
}