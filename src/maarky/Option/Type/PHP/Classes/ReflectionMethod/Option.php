<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\ReflectionMethod;

abstract class Option extends \maarky\Option\Type\PHP\Classes\ReflectionFunctionAbstract\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \ReflectionMethod && parent::isValid($value);
    }
}