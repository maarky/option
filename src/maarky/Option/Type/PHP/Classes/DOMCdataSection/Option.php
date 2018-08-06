<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\DOMCdataSection;

abstract class Option extends \maarky\Option\Type\PHP\Classes\DOMText\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \DOMCdataSection && parent::isValid($value);
    }
}