<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\FilesystemIterator;

abstract class Option extends \maarky\Option\Type\PHP\Classes\DirectoryIterator\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \FilesystemIterator && parent::isValid($value);
    }
}