<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\ReflectionObject;

abstract class Option extends \maarky\Option\Type\PHP\Classes\ReflectionClass\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \ReflectionObject && parent::isValid($value);
    }
}