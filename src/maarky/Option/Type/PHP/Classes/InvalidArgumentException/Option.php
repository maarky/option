<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\InvalidArgumentException;

abstract class Option extends \maarky\Option\Type\PHP\Classes\LogicException\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \InvalidArgumentException && parent::isValid($value);
    }
}