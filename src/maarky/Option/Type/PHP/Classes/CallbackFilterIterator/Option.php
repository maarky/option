<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\CallbackFilterIterator;

abstract class Option extends \maarky\Option\Type\PHP\Classes\FilterIterator\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \CallbackFilterIterator && parent::isValid($value);
    }
}