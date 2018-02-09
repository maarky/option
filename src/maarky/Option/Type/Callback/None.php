<?php
declare(strict_types=1);

namespace maarky\Option\Type\Callback;

use maarky\Option\Component\BaseNone;

class None extends Option
{
    use BaseNone;

    public function get(): callable
    {
        return $this;
    }

    public function getOrElse($else): callable
    {
        return $else;
    }

    public function getOrCall(callable $call): callable
    {
        return $call();
    }
}