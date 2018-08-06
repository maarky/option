<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\Error;

abstract class Option extends \maarky\Option\Type\Object\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \Error && parent::isValid($value);
    }
}