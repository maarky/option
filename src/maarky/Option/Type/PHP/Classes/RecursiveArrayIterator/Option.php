<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\RecursiveArrayIterator;

abstract class Option extends \maarky\Option\Type\PHP\Classes\ArrayIterator\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \RecursiveArrayIterator && parent::isValid($value);
    }
}