<?php
declare(strict_types=1);

namespace maarky\Option;

abstract class Option
{
    public static function validate($value): bool
    {
        return !is_null($value);
    }

    public static function new($value)
    {
        $namespace = get_called_class()::getCalledNamespace();
        if(get_called_class()::validate($value)) {
            $someClass = $namespace . '\\Some';
            return new $someClass($value);
        }
        $noneClass = $namespace . '\\None';
        return new $noneClass;
    }

    protected static function getCalledNamespace()
    {
        $classParts = explode('\\', get_called_class());
        array_pop($classParts);
        return implode('\\', $classParts);
    }

    abstract public function get();

    /**
     * @param mixed $else
     * @return mixed
     */
    abstract public function getOrElse($else);

    /**
     * @param callable $call
     * @return mixed
     */
    abstract public function getOrCall(callable $call);

    /**
     * @param Option $else
     * @return Option
     */
    abstract public function orElse(Option $else): Option;

    /**
     * @param callable $else
     * @return Option
     */
    abstract public function orCall(callable $else): Option;

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
}