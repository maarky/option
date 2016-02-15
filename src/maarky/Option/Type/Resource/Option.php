<?php

namespace maarky\Option\Type\Resource;

abstract class Option extends \maarky\Option\Option
{
    protected function validate($value): bool
    {
        return is_resource($value);
    }
}