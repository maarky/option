<?php

namespace maarky\Option\Type\Callback;

abstract class Option extends \maarky\Option\Option
{
    protected function validate($value): bool
    {
        return is_callable($value);
    }
}