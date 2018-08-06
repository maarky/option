<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\DOMEntityReference;

abstract class Option extends \maarky\Option\Type\PHP\Classes\DOMNode\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \DOMEntityReference && parent::isValid($value);
    }
}