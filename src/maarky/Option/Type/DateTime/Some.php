<?php
declare(strict_types=1);

namespace maarky\Option\Type\DateTime;

use maarky\Option\Component\BaseSome;

class Some extends Option
{
    use BaseSome;

    public function get(): \DateTime
    {
        return $this->value;
    }
}
