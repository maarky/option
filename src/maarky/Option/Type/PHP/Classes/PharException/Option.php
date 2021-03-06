<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\PharException;

abstract class Option extends \maarky\Option\Type\PHP\Classes\Exception\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \PharException && parent::isValid($value);
    }
}