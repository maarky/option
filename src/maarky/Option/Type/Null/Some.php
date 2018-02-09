<?php
declare(strict_types=1);

namespace maarky\Option\Type\Null;

use maarky\Option\Component\BaseSome;

/**
 * @method null get()
 */
class Some extends Option
{
    use BaseSome;
}
