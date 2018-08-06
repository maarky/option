<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\PDOException;

abstract class Option extends \maarky\Option\Type\PHP\Classes\RuntimeException\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \PDOException && parent::isValid($value);
    }
}