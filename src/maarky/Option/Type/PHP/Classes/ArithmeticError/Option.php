<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\ArithmeticError;

abstract class Option extends \maarky\Option\Type\PHP\Classes\Error\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \ArithmeticError && parent::isValid($value);
    }
}