<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\PDOStatement;

abstract class Option extends \maarky\Option\Type\Object\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \PDOStatement && parent::isValid($value);
    }
}