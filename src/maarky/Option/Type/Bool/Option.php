<?php
declare(strict_types=1);

namespace maarky\Option\Type\Bool;

abstract class Option extends \maarky\Option\Option
{
    public static function validate($value): bool
    {
        return is_bool($value);
    }
}