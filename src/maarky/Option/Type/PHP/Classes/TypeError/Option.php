<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\TypeError;

abstract class Option extends \maarky\Option\Type\PHP\Classes\Error\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \TypeError && parent::isValid($value);
    }
}