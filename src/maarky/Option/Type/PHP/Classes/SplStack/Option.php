<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\SplStack;

abstract class Option extends \maarky\Option\Type\PHP\Classes\SplDoublyLinkedList\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \SplStack && parent::isValid($value);
    }
}