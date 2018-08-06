<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\BadMethodCallException;

abstract class Option extends \maarky\Option\Type\PHP\Classes\BadFunctionCallException\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \BadMethodCallException && parent::isValid($value);
    }
}