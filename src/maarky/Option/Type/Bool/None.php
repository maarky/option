<?php
declare(strict_types=1);

namespace maarky\Option\Type\Bool;

use maarky\Option\Component\BaseNone;

class None extends Option
{
    use BaseNone;

    public function get(): bool
    {
        return $this;
    }

    public function getOrElse($else): bool
    {
        return $else;
    }

    public function getOrCall(callable $call): bool
    {
        return $call();
    }
}