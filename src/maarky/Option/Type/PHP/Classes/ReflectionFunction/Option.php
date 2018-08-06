<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\ReflectionFunction;

abstract class Option extends \maarky\Option\Type\PHP\Classes\ReflectionFunctionAbstract\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \ReflectionFunction && parent::isValid($value);
    }
}