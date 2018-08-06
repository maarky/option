<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\BadFunctionCallException;

abstract class Option extends \maarky\Option\Type\PHP\Classes\LogicException\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \BadFunctionCallException && parent::isValid($value);
    }
}