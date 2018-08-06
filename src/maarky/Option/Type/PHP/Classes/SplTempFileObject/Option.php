<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\SplTempFileObject;

abstract class Option extends \maarky\Option\Type\PHP\Classes\SplFileObject\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \SplTempFileObject && parent::isValid($value);
    }
}