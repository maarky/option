<?php
declare(strict_types=1);

namespace maarky\Option\Type\DateTime;

use maarky\Option\Component\BaseNone;

class None extends Option
{
    use BaseNone;

    public function get(): \DateTime
    {
        return $this;
    }

    public function getOrElse($else): \DateTime
    {
        return $else;
    }

    public function getOrCall(callable $call): \DateTime
    {
        return $call();
    }
}