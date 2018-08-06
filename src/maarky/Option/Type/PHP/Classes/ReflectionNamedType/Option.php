<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\ReflectionNamedType;

abstract class Option extends \maarky\Option\Type\PHP\Classes\ReflectionType\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \ReflectionNamedType && parent::isValid($value);
    }
}