<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\DOMElement;

abstract class Option extends \maarky\Option\Type\PHP\Classes\DOMNode\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \DOMElement && parent::isValid($value);
    }
}