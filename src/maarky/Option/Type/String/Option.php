<?php


namespace maarky\Option\Type\String;

abstract class Option extends \maarky\Option\Option
{
    protected function validate($value): bool
    {
        return is_string($value);
    }
}