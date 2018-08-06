<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\FilterIterator;

abstract class Option extends \maarky\Option\Type\PHP\Classes\IteratorIterator\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \FilterIterator && parent::isValid($value);
    }
}