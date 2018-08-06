<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\DOMCharacterData;

abstract class Option extends \maarky\Option\Type\PHP\Classes\DOMNode\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \DOMCharacterData && parent::isValid($value);
    }
}