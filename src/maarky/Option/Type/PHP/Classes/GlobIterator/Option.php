<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\GlobIterator;

abstract class Option extends \maarky\Option\Type\PHP\Classes\FilesystemIterator\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \GlobIterator && parent::isValid($value);
    }
}