<?php
declare(strict_types=1);

namespace maarky\Option\Type\Arr;

use maarky\Option\Component\BaseSome;

class Some extends Option
{
    use BaseSome;

    public function get(): array
    {
        return $this->value;
    }
}
