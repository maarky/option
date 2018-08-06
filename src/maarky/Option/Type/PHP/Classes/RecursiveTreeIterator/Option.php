<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\RecursiveTreeIterator;

abstract class Option extends \maarky\Option\Type\PHP\Classes\RecursiveIteratorIterator\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \RecursiveTreeIterator && parent::isValid($value);
    }
}