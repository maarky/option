<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\UnexpectedValueException;

abstract class Option extends \maarky\Option\Type\PHP\Classes\RuntimeException\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \UnexpectedValueException && parent::isValid($value);
    }
}