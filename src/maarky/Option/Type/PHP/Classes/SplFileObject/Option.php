<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\SplFileObject;

abstract class Option extends \maarky\Option\Type\PHP\Classes\SplFileInfo\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \SplFileObject && parent::isValid($value);
    }
}