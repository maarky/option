<?php

namespace maarky\Option\Component;

use maarky\Option\Option;
use maarky\Option\Some;

trait BaseSome
{
    public function __construct($value)
    {
        if(!$this->validate($value)) {
            throw new \TypeError;
        }
        $this->value = $value;
    }

    public function get()
    {
        return $this->value;
    }

    public function getOrElse($else)
    {
        return $this->get();
    }

    /**
     * @param Option $else
     * @return Option
     * @throws \TypeError
     */
    public function orElse(Option $else): Option
    {
        return $this;
    }

    public function orElseCall(callable $else): Option
    {
        return $this;
    }

    /**
     * @return bool
     */
    public function isNone(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isSome(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isDefined(): bool
    {
        return $this->isSome();
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->isNone();
    }

    /**
     * @param callable $filter returns boolean
     * @return Option
     */
    public function filter(callable $filter): Option
    {
        if(true === $filter($this->value)) {
            return $this;
        }
        return $this->getNone();
    }

    /**
     * @param callable $map
     * @return Option
     */
    public function flatMap(callable $map): Option
    {
        $mapped = $map($this->value);
        if($mapped instanceof Option && $mapped->isNone()) {
            return $mapped;
        }
        $option = null;
        while($mapped instanceof Option && $mapped->isSome()) {
            $option = $mapped;
            $mapped = $mapped->get();
        }
        if($option instanceof Option && $option->isNone()) {
            return $option;
        } elseif($mapped instanceof Option && $mapped->isNone()) {
            return $mapped;
        }
        if($this->validate($mapped)) {
            return new self($mapped);
        }
        return new Some($mapped);
    }

    /**
     * @param callable $each
     * @return void
     */
    public function foreach (callable $each)
    {
        $each($this->value);
    }

    /**
     * @param callable $map
     * @return Option
     */
    public function map(callable $map): Option
    {
        $mapped = $map($this->value);
        if($this->validate($mapped)) {
            return new self($mapped);
        }
        return new Some($mapped);
    }

    /**
     * @param Option $option
     * @return bool
     */
    public function equals(Option $option): bool
    {
        return $option instanceof self && $this->value === $option->get();
    }
}