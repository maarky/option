<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\SimpleXMLIterator;

abstract class Option extends \maarky\Option\Type\PHP\Classes\SimpleXMLElement\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \SimpleXMLIterator && parent::isValid($value);
    }
}