<?php
declare(strict_types=1);

namespace maarky\Option\Type\String;

use maarky\Option\Component\BaseNone;

class None extends Option
{
    use BaseNone;

    public function get(): string
    {
        return $this;
    }

    public function getOrElse($else): string
    {
        return $else;
    }

    public function getOrCall(callable $call): string
    {
        return $call();
    }
}