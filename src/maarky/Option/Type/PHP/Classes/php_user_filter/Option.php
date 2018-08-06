<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\php_user_filter;

abstract class Option extends \maarky\Option\Type\Object\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \php_user_filter && parent::isValid($value);
    }
}