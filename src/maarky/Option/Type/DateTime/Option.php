<?php

namespace maarky\Option\Type\DateTime;

use DateTime;

abstract class Option extends \maarky\Option\Type\Object\Option
{
    protected function validate($value): bool
    {
        return parent::validate($value) && $value instanceof DateTime;
    }
}