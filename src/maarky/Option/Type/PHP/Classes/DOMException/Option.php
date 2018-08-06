<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\DOMException;

abstract class Option extends \maarky\Option\Type\PHP\Classes\Exception\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \DOMException && parent::isValid($value);
    }
}