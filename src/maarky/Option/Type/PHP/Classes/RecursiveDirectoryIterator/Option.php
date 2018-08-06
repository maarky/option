<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\RecursiveDirectoryIterator;

abstract class Option extends \maarky\Option\Type\PHP\Classes\FilesystemIterator\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \RecursiveDirectoryIterator && parent::isValid($value);
    }
}