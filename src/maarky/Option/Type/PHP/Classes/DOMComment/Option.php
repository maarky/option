<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\DOMComment;

abstract class Option extends \maarky\Option\Type\PHP\Classes\DOMCharacterData\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \DOMComment && parent::isValid($value);
    }
}