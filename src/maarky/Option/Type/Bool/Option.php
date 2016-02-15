<?php

namespace maarky\Option\Type\Bool;

abstract class Option extends \maarky\Option\Option
{
    protected function validate($value): bool
    {
        return is_bool($value);
    }
}