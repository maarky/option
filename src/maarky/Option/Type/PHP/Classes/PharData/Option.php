<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\PharData;

abstract class Option extends \maarky\Option\Type\PHP\Classes\RecursiveDirectoryIterator\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \PharData && parent::isValid($value);
    }
}