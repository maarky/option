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
    /**
     * @var string
     */
    protected $some;

    protected function validate($value): bool
    {
        return true;
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
            $noneClass = $this->getOption('none');
            $this->none = new $noneClass;
        }
        return $this->none;
    }

    protected function getSome($value)
    {
        if($this->validate($value)) {
            if(null == $this->some) {
                $this->some = $this->getOption('some');
            }
            $someClass = $this->some;
            return new $someClass($value);
        }
        return new Some($value);
    }

    protected function getOption(string $type)
    {
        $type = strtolower($type);
        if(!in_array($type, ['some', 'none'])) {
            throw new \InvalidArgumentException;
        }
        $className = get_class($this);
        $classNameParts = explode('\\', $className);
        array_pop($classNameParts);
        $classNameParts[] = ucwords($type);
        return implode('\\', $classNameParts);
    }
}