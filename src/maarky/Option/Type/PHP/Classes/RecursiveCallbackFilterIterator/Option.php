<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\RecursiveCallbackFilterIterator;

abstract class Option extends \maarky\Option\Type\PHP\Classes\CallbackFilterIterator\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \RecursiveCallbackFilterIterator && parent::isValid($value);
    }
}