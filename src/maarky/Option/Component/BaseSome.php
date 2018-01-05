<?php
declare(strict_types=1);

namespace maarky\Option\Component;

use maarky\Option\Option;
use maarky\Option\Some;

trait BaseSome
{
    protected $value;

    public function __construct($value)
    {
        if(!self::validate($value)) {
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

    public function getOrCall(callable $call)
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

    public function orCall(callable $else): Option
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
     * @param callable $filter returns boolean
     * @return Option
     */
    public function filter(callable $filter): Option
    {
        if(true === $filter($this->value)) {
            return $this;
        }
        $noneClass = self::getCalledNamespace() . '\\None';
        return new $noneClass;
    }

    /**
     * @param callable $filter returns boolean
     * @return Option
     */
    public function filterNot(callable $filter): Option
    {
        if(false === $filter($this->value)) {
            return $this;
        }
        $noneClass = self::getCalledNamespace() . '\\None';
        return new $noneClass;
    }

    /**
     * @param callable $each
     * @return void
     */
    public function foreach (callable $each): Option
    {
        $each($this->value);
        return $this;
    }

    /**
     * @param callable $none
     * @return Option
     */
    public function fornone(callable $none): Option
    {
        return $this;
    }

    /**
     * @param callable $map
     * @return Option
     */
    public function map(callable $map): Option
    {
        $mapped = $map($this->value);
        $self = self::getCalledNamespace() . '\\Some';
        if($self::validate($mapped)) {
            return new $self($mapped);
        }
        return new Some($mapped);
    }

    /**
     * @param callable $map
     * @return Option
     */
    public function flatMap(callable $map): Option
    {
        return $map($this->value, $this);
    }

    /**
     * @param Option $option
     * @return bool
     */
    public function equals(Option $option): bool
    {
        $self = self::getCalledNamespace() . '\\Some';
        return $option instanceof $self && $this->value === $option->get();
    }
}