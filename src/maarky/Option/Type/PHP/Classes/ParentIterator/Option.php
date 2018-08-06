<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\ParentIterator;

abstract class Option extends \maarky\Option\Type\PHP\Classes\RecursiveFilterIterator\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \ParentIterator && parent::isValid($value);
    }
}