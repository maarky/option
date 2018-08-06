<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\DivisionByZeroError;

abstract class Option extends \maarky\Option\Type\PHP\Classes\ArithmeticError\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \DivisionByZeroError && parent::isValid($value);
    }
}