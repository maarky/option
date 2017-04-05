<?php

namespace maarky\Option\Type\Arr;

abstract class Option extends \maarky\Option\Option
{
    public static function validate($value): bool
    {
        return is_array($value);
    }
}