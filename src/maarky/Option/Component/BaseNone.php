<?php
declare(strict_types=1);

namespace maarky\Option\Component;

use maarky\Option\Option;
use maarky\Option\Some;

trait BaseNone
{
    public function get()
    {
        return $this;
    }

    public function getOrElse($else)
    {
        return $else;
    }

    public function getOrCall(callable $call)
    {
        return $call();
    }

    public function orCall(callable $else): Option
    {
        $option = $else();
        if(!$option instanceof Option) {
            throw new \UnexpectedValueException('callable must return an option.');
        }
        return $option;
    }

    /**
     * @param Option $else
     * @return Option
     * @throws \TypeError
     */
    public function orElse(Option $else): Option
    {
        return $else;
    }

    /**
     * @return bool
     */
    public function isNone(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isSome(): bool
    {
        return false;
    }

    /**
     * @param callable $filter returns boolean
     * @return Option
     */
    public function filter(callable $filter): Option
    {
        return $this;
    }

    /**
     * @param callable $filter returns boolean
     * @return Option
     */
    public function filterNot(callable $filter): Option
    {
        return $this;
    }

    /**
     * @param callable $map
     * @return Option
     */
    public function flatMap(callable $map): Option
    {
        return $this;
    }

    /**
     * @param callable $each
     * @return void
     */
    public function foreach (callable $each)
    {
        return $this;
    }

    /**
     * @param callable $map
     * @return Option
     */
    public function map(callable $map): Option
    {
        return $this;
    }

    /**
     * @param Option $option
     * @return bool
     */
    public function equals(Option $option): bool
    {
        return $option instanceof self;
    }
}