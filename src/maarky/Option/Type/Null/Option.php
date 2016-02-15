<?php

namespace maarky\Option\Type\Null;

abstract class Option extends \maarky\Option\Option
{
    protected function validate($value): bool
    {
        return is_null($value);
    }
}