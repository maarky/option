<?php
declare(strict_types=1);

namespace maarky\Option\Type\Int;

use maarky\Option\Component\BaseNone;

class None extends Option
{
    use BaseNone {
        BaseNone::getOrElse as _getOrElse;
        BaseNone::getOrCall as _getOrCall;
    }

    public function get(): int
    {
        return $this;
    }

    public function getOrElse($else): int
    {
        return $this->_getOrElse($else);
    }

    public function getOrCall(callable $call): int
    {
        return $this->_getOrCall($call);
    }
}