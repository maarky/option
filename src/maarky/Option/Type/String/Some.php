<?php
declare(strict_types=1);

namespace maarky\Option\Type\String;

use maarky\Option\Component\BaseSome;

class Some extends Option
{
    use BaseSome;

    public function get(): string
    {
        return $this->value;
    }
}
