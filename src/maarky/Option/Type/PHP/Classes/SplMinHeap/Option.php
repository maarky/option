<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\SplMinHeap;

abstract class Option extends \maarky\Option\Type\PHP\Classes\SplHeap\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \SplMinHeap && parent::isValid($value);
    }
}