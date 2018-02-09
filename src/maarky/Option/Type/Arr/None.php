<?php
declare(strict_types=1);

namespace maarky\Option\Type\Arr;

use maarky\Option\Component\BaseNone;

class None extends Option
{
    use BaseNone;

    public function get(): array
    {
        return $this;
    }

    public function getOrElse($else): array
    {
        return $else;
    }

    public function getOrCall(callable $call): array
    {
        return $call();
    }
}