<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\Closure;

abstract class Option extends \maarky\Option\Type\Object\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \Closure && parent::isValid($value);
    }
}