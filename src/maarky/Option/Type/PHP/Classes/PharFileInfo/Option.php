<?php
declare(strict_types=1);

namespace maarky\Option\Type\PHP\Classes\PharFileInfo;

abstract class Option extends \maarky\Option\Type\PHP\Classes\SplFileInfo\Option
{
    public static function isValid($value): bool
    {
        return $value instanceof \PharFileInfo && parent::isValid($value);
    }
}