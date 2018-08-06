<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\RecursiveCachingIterator;

abstract class Option extends \maarky\Option\Type\PHP\Classes\CachingIterator\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \RecursiveCachingIterator && parent::isValid($value);
    }
}