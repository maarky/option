<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\DateTime;

abstract class Option extends \maarky\Option\Type\Object\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \DateTime && parent::isValid($value);
    }
}