<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\ArgumentCountError;

abstract class Option extends \maarky\Option\Type\PHP\Classes\TypeError\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \ArgumentCountError && parent::isValid($value);
    }
}