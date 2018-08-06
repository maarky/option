<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\RecursiveRegexIterator;

abstract class Option extends \maarky\Option\Type\PHP\Classes\RegexIterator\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \RecursiveRegexIterator && parent::isValid($value);
    }
}