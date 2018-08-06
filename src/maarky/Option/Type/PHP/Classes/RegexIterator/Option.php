<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\RegexIterator;

abstract class Option extends \maarky\Option\Type\PHP\Classes\FilterIterator\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \RegexIterator && parent::isValid($value);
    }
}