<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\DOMDocument;

abstract class Option extends \maarky\Option\Type\PHP\Classes\DOMNode\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \DOMDocument && parent::isValid($value);
    }
}