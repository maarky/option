<?php
declare(strict_types=1);

namespace maarky\Test\Option;

use PHPUnit\Framework\TestCase;
use maarky\Option\Option;
use maarky\Option\Some;
use maarky\Option\Type\Bool\Option as BoolOption;

class SomeTest extends TestCase
{
    public function testGet()
    {
        $value = 1;
        $some = Option::new($value);
        $this->assertEquals($value, $some->get());
    }

    public function testGetOrElse()
    {
        $value = 1;
        $some = Option::new($value);
        $this->assertEquals($value, $some->getorElse(2));
    }

    public function testGetOrCall()
    {
        $value = 1;
        $some = Option::new($value);
        $this->assertEquals($value, $some->getorCall(function() { return 5; }));
    }

    public function testGetOrElse_notElse()
    {
        $value = 1;
        $else = 2;
        $some = Option::new($value);
        $this->assertNotEquals($else, $some->getorElse($else));
    }

    public function testFlatMap()
    {
        $value = 2;
        $double = function(int $num) { return Option::new($num * 2); };
        $some = Option::new($value);
        $expected = $double($value, $some);
        $this->assertEquals($expected, $some->flatMap($double));
    }

    public function testFlatMap_mustReturnProperSome()
    {
        $expected = BoolOption::new(true);
        $function = function() use($expected) { return $expected; };
        $some = Option::new(5);
        $this->assertEquals($expected, $some->flatMap($function));
    }

    public function testFlatMap_mustReturnProperNone()
    {
        $expected = BoolOption::new(null);
        $function = function() use($expected) { return $expected; };
        $some = Option::new(5);
        $this->assertEquals($expected, $some->flatMap($function));
    }

    public function testFlatMap_mustNotReturnOptionWithOption()
    {
        $value = 2;
        $mapValue = 4;
        $option = function(int $num) use($mapValue) { return Option::new($mapValue); };
        $expected = Option::new($mapValue);
        $some = Option::new($value);
        $this->assertEquals($expected, $some->flatMap($option));
    }

    public function testFlatMap_returnsNone()
    {
        $none = Option::new(null);
        $option = function() use($none) { return $none; };
        $some = Option::new(2);
        $this->assertEquals($none, $some->flatMap($option));
    }

    public function testFlatMap_withSomeNone()
    {
        $expected = Option::new(Option::new(null));
        $map = function() use($expected) { return $expected; };
        $some = Option::new(2);
        $this->assertEquals($expected, $some->flatMap($map));
    }

    public function testFlatMap_callbackMustReturnOption()
    {
        $this->expectException('TypeError');
        $some = Option::new(2);
        $some->flatMap(function() { return 1; });
    }

    public function testMap()
    {
        $value = 2;
        $double = function(int $num) { return $num * 2; };
        $expected = Option::new($double($value));
        $some = Option::new($value);
        $this->assertEquals($expected, $some->map($double));
    }

    public function testMap_callbackString()
    {
        $value = 'hello';
        $some = Option::new($value);
        $expected = Option::new(strtolower($value));
        $this->assertEquals($expected, $some->map('strtolower'));
    }

    public function testMap_mayReturnOptionWithSome()
    {
        $value = 2;
        $mapped = Option::new(4);
        $map = function() use($mapped) { return $mapped; };
        $expected = Option::new($mapped);
        $some = Option::new($value);
        $this->assertEquals($expected, $some->map($map));
    }

    public function testMap_mayReturnOptionWithNone()
    {
        $value = 2;
        $mapped = Option::new(null);
        $map = function() use($mapped) { return $mapped; };
        $expected = Option::new($mapped);
        $some = Option::new($value);
        $this->assertEquals($expected, $some->map($map));
    }

    public function testFilter()
    {
        $filter = function() { return true; };
        $some = Option::new(2);
        $this->assertEquals($some, $some->filter($filter));
    }

    public function testFilter_false()
    {
        $filter = function() { return false; };
        $some = Option::new(2);
        $this->assertFalse($some->filter($filter)->isDefined());
    }

    public function testFilter_callbackArray_true()
    {
        $some = Option::new(1);
        $this->assertEquals($some, $some->filter(array($this, 'callbackTrue')));
    }

    public function testFilter_callbackArray_false()
    {
        $some = Option::new(1);
        $this->assertInstanceOf('maarky\Option\None', $some->filter(array($this, 'callbackFalse')));
    }

    public function testFilterNot()
    {
        $filter = function() { return false; };
        $some = Option::new(2);
        $this->assertEquals($some, $some->filterNot($filter));
    }

    public function testFilterNot_true()
    {
        $filter = function() { return true; };
        $some = Option::new(2);
        $this->assertEquals(Option::new(null), $some->filterNot($filter));
    }

    public function testForEach()
    {
        $some = Option::new(1);
        $callable = new class() {
            public $called = false;

            public function __invoke()
            {
                $this->called = true;
            }
        };
        $some->foreach($callable);
        $this->assertTrue($callable->called);
    }

    public function testForNone()
    {
        $some = Option::new(1);
        $callable = new class() {
            public $called = false;

            public function __invoke()
            {
                $this->called = true;
            }
        };
        $some->fornothing($callable);
        $this->assertFalse($callable->called);
    }

    public function testOrElse()
    {
        $some = Option::new(1);
        $else = Option::new(2);
        $this->assertSame($some, $some->orElse($else));
    }

    public function testOrCall()
    {
        $some = Option::new(1);
        $this->assertSame($some, $some->orCall(function(){}));
    }

    public function testIsDefined()
    {
        $some = Option::new(1);
        $this->assertTrue($some->isDefined());
    }

    public function testIsEmpty()
    {
        $some = Option::new(1);
        $this->assertFalse($some->isEmpty());
    }

    public function testEquals()
    {
        $some = Option::new(1);
        $some2 = Option::new(1);
        $this->assertTrue($some->equals($some2));
    }

    public function testEquals_false()
    {
        $some = Option::new(1);
        $other = Option::new(2);
        $this->assertFalse($some->equals($other));
    }

    public function testEquals_false_typeCheck()
    {
        $some = Option::new(1);
        $other = Option::new('1');
        $this->assertFalse($some->equals($other));
    }

    public function callbackTrue()
    {
        return true;
    }

    public function callbackFalse()
    {
        return false;
    }
}