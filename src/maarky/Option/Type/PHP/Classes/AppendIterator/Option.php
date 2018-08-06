<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\AppendIterator;

abstract class Option extends \maarky\Option\Type\PHP\Classes\IteratorIterator\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \AppendIterator && parent::isValid($value);
    }
}