<?php
declare(strict_types=1);

namespace maarky\Option\Component;

use maarky\Monad\SingleContainer;
use maarky\Option\Option;

trait BaseSome
{
    protected $value;

    /**
     * BaseSome constructor.
     * @param $value
     * @throws \TypeError
     */
    final protected function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->value;
    }

    /**
     * @param $else
     * @return mixed
     */
    public function getOrElse($else)
    {
        return $this->get();
    }

    /**
     * @param callable $call
     * @return mixed
     */
    public function getOrCall(callable $call)
    {
        return $this->get();
    }

    /**
     * @param SingleContainer $else
     * @return SingleContainer
     */
    public function orElse(SingleContainer $else): SingleContainer
    {
        return $this;
    }

    /**
     * @param callable $else
     * @return SingleContainer
     */
    public function orCall(callable $else): SingleContainer
    {
        return $this;
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isDefined(): bool
    {
        return true;
    }

    /**
     * @param callable $filter returns boolean
     * @return SingleContainer
     */
    public function filter(callable $filter): SingleContainer
    {
        if(true === $filter($this->value, $this)) {
            return $this;
        }

        return static::new(null, function() { return false; });
    }

    /**
     * @param callable $filter returns boolean
     * @return SingleContainer
     */
    public function filterNot(callable $filter): SingleContainer
    {
        if(false === $filter($this->value, $this)) {
            return $this;
        }
        return static::new(null, function() { return false; });
    }

    /**
     * @param callable $each
     * @return SingleContainer
     */
    public function foreach (callable $each): SingleContainer
    {
        $each($this->value, $this);
        return $this;
    }

    /**
     * @param callable $none
     * @return SingleContainer
     */
    public function fornothing(callable $none): SingleContainer
    {
        return $this;
    }

    /**
     * @param callable $map
     * @return Option
     * @throws \ReflectionException
     */
    public function map(callable $map): SingleContainer
    {
        $mapped = (1 == (new \ReflectionFunction($map))->getNumberOfRequiredParameters()) ?
            $map($this->value) : $map($this->value, $this);
        return static::new($mapped);
    }

    /**
     * @param callable $map
     * @return SingleContainer
     */
    public function flatMap(callable $map): SingleContainer
    {
        return $map($this->value, $this);
    }

    /**
     * @param Option $option
     * @return bool
     */
    public function equals($option): bool
    {
        return $option instanceof static && $this->value === $option->get();
    }
}