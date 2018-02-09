<?php
declare(strict_types=1);

namespace maarky\Option\Type\Object;

abstract class Option extends \maarky\Option\Option
{
    public static function validate($value): bool
    {
        return is_object($value);
    }
}