<?php

namespace Framework\ORM\Entities;

use Framework\ORM\Interfaces\Entity;

class BasicEntity implements Entity {
    public static function getClass() {
        return static::class;
    }

    private $id;
}