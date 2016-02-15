<?php
declare(strict_types=1);

namespace maarky\Option;

abstract class Option
{
    protected $value;
    /**
     * @var None
     */
    protected $none;

    protected function validate($value): bool
    {
        return true;
    }

    abstract public function get();

    /**
     * $else must return an Option
     *
     * @param callable $else
     * @return Option
     */
    abstract public function getOrElse($else);

    /**
     * @param Option $else
     * @return Option
     */
    abstract public function orElse(Option $else): Option;

    /**
     * @param callable $else
     * @return Option
     */
    abstract public function orElseCall(callable $else): Option;

    /**
     * @return bool
     */
    abstract public function isNone(): bool;

    /**
     * @return bool
     */
    abstract public function isSome(): bool;

    /**
     * @return bool
     */
    abstract public function isDefined(): bool;

    /**
     * @return bool
     */
    abstract public function isEmpty(): bool;

    /**
     * @param callable $filter returns boolean
     * @return Option
     */
    abstract public function filter(callable $filter): Option;

    /**
     * @param callable $map
     * @return Option
     */
    abstract public function flatMap(callable $map): Option;

    /**
     * @param callable $each
     * @return void
     */
    abstract public function foreach(callable $each);

    /**
     * @param callable $map
     * @return Option
     */
    abstract public function map(callable $map): Option;

    /**
     * @param Option $option
     * @return bool
     */
    abstract public function equals(Option $option): bool;

    protected function getNone()
    {
        if($this->isNone()) {
            return $this;
        }
        if(null == $this->none) {
            $className = get_class($this);
            $classNameParts = explode('\\', $className);
            array_pop($classNameParts);
            $classNameParts[] = 'None';
            $noneClass = implode('\\', $classNameParts);
            $this->none = new $noneClass;
        }
        return $this->none;
    }
}