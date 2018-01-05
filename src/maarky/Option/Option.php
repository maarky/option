<?php
declare(strict_types=1);

namespace maarky\Option;

abstract class Option
{
    public static function validate($value): bool
    {
        return !is_null($value);
    }

    /**
     * @param $value
     * @param callable|null $filter Must take $value as arg and return a boolean
     * @return Option
     */
    final public static function new($value, callable $filter = null): Option
    {
        $namespace = get_called_class()::getCalledNamespace();
        $filter = $filter ?: function() { return true; };
        if(get_called_class()::validate($value) && $filter($value)) {
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
     * @param callable $filter returns boolean
     * @return Option
     */
    abstract public function filterNot(callable $filter): Option;

    /**
     * @param callable $map
     * @return Option
     */
    abstract public function flatMap(callable $map): Option;

    /**
     * @param callable $each
     * @return Option
     */
    abstract public function foreach(callable $each): Option;

    /**
     * @param callable $none
     * @return Option
     */
    abstract public function fornone(callable $none): Option;

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