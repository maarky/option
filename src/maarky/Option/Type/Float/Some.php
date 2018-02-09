<?php
declare(strict_types=1);

namespace maarky\Option\Type\Float;

use maarky\Option\Component\BaseSome;

class Some extends Option
{
    use BaseSome;

    public function get(): float
    {
        return $this->value;
    }
}
