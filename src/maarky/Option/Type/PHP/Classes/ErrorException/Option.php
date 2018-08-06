<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\ErrorException;

abstract class Option extends \maarky\Option\Type\PHP\Classes\Exception\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \ErrorException && parent::isValid($value);
    }
}