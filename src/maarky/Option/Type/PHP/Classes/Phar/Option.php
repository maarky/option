<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\Phar;

abstract class Option extends \maarky\Option\Type\PHP\Classes\RecursiveDirectoryIterator\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \Phar && parent::isValid($value);
    }
}