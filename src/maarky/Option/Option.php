<?php
declare(strict_types=1);

namespace maarky\Option;

use maarky\Monad\SingleContainer;

abstract class Option implements SingleContainer
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
        $filter = $filter ?: function() { return true; };
        if(static::validate($value) && $filter($value)) {
            $class = static::getCalledNamespaceOptionClass('Some');
            return new $class($value);
        }
        $class = static::getCalledNamespaceOptionClass('None');
        return new $class();
    }

    final protected static function getCalledNamespaceOptionClass(string $type)
    {
        $classParts = explode('\\', static::class);
        $classParts[count($classParts) - 1] = $type;
        return implode('\\', $classParts);
    }
}