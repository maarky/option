<?php
declare(strict_types=1);

namespace maarky\Option\Type\DateTimeImmutable;

use maarky\Option\Type\Object\Option as ObjectOption;

abstract class Option extends ObjectOption
{
    public static function validate($value): bool
    {
        return $value instanceof \DateTimeImmutable;
    }
}