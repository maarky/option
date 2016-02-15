<?php

namespace maarky\Option\Type\Float;

abstract class Option extends \maarky\Option\Option
{
    protected function validate($value): bool
    {
        return is_float($value);
    }
}