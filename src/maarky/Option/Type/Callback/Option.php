<?php

namespace maarky\Option\Type\Callback;

abstract class Option extends \maarky\Option\Option
{
    public static function validate($value): bool
    {
        return is_callable($value);
    }
}