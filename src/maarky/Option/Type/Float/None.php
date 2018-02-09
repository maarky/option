<?php
declare(strict_types=1);

namespace maarky\Option\Type\Float;

use maarky\Option\Component\BaseNone;

class None extends Option
{
    use BaseNone {
        BaseNone::getOrElse as _getOrElse;
        BaseNone::getOrCall as _getOrCall;
    }

    public function get(): float
    {
        return $this;
    }

    public function getOrElse($else): float
    {
        return $this->_getOrElse($else);
    }

    public function getOrCall(callable $call): float
    {
        return $this->_getOrCall($call);
    }
}