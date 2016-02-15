<?php

namespace maarky\Option\Type\Int;

abstract class Option extends \maarky\Option\Option
{
    protected function validate($value): bool
    {
        return is_int($value);
    }
}