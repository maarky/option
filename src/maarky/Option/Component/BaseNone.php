<?php
declare(strict_types=1);

namespace maarky\Option\Component;

use maarky\Monad\SingleContainer;
use maarky\Option\Option;

trait BaseNone
{
    final protected function __construct() {}

    /**
     * @throws \TypeError
     */
    public function get()
    {
        throw new \TypeError();
    }

    /**
     * @param $else
     * @return mixed
     * @throws \TypeError
     */
    public function getOrElse($else)
    {
        if(!static::validate($else)) {
            throw new \TypeError();
        }
        return $else;
    }

    /**
     * @param callable $call
     * @return mixed
     * @throws \TypeError
     */
    public function getOrCall(callable $call)
    {
        $else = $call($this);
        if(!static::validate($else)) {
            throw new \TypeError();
        }
        return $else;
    }

    /**
     * @param callable $else
     * @return SingleContainer
     */
    public function orCall(callable $else): SingleContainer
    {
        return $else($this);
    }

    /**
     * @param SingleContainer $else
     * @return SingleContainer
     */
    public function orElse(SingleContainer $else): SingleContainer
    {
        return $else;
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isDefined(): bool
    {
        return false;
    }

    /**
     * @param callable $filter
     * @return SingleContainer
     */
    public function filter(callable $filter): SingleContainer
    {
        return $this;
    }

    /**
     * @param callable $filter returns boolean
     * @return SingleContainer
     */
    public function filterNot(callable $filter): SingleContainer
    {
        return $this;
    }

    /**
     * @param callable $map
     * @return SingleContainer
     */
    public function map(callable $map): SingleContainer
    {
        return $this;
    }

    /**
     * @param callable $map
     * @return SingleContainer
     */
    public function flatMap(callable $map): SingleContainer
    {
        return $this;
    }

    /**
     * @param callable $each
     * @return SingleContainer
     */
    public function foreach (callable $each): SingleContainer
    {
        return $this;
    }

    /**
     * @param callable $none
     * @return SingleContainer
     */
    public function fornothing(callable $none): SingleContainer
    {
        $none($this);
        return $this;
    }

    /**
     * @param Option $option
     * @return bool
     */
    public function equals($option): bool
    {
        return $option instanceof self;
    }
}