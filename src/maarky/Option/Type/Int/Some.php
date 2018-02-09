<?php
declare(strict_types=1);

namespace maarky\Option\Type\Int;

use maarky\Option\Component\BaseSome;

class Some extends Option
{
    use BaseSome;

    public function get(): int
    {
        return $this->value;
    }
}
