<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\CachingIterator;

abstract class Option extends \maarky\Option\Type\PHP\Classes\IteratorIterator\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \CachingIterator && parent::isValid($value);
    }
}