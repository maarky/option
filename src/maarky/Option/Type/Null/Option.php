<?php

namespace maarky\Option\Type\Null;

abstract class Option extends \maarky\Option\Option
{
    public static function validate($value): bool
    {
        return is_null($value);
    }
}