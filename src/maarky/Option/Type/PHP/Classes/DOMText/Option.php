<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\DOMText;

abstract class Option extends \maarky\Option\Type\PHP\Classes\DOMCharacterData\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \DOMText && parent::isValid($value);
    }
}