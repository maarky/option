<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\DOMAttr;

abstract class Option extends \maarky\Option\Type\PHP\Classes\DOMNode\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \DOMAttr && parent::isValid($value);
    }
}