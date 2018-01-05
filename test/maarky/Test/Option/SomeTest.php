<?php
declare(strict_types=1);

namespace maarky\Test\Option;

use PHPUnit\Framework\TestCase;
use maarky\Option\None;
use maarky\Option\Some;
use maarky\Option\Type\Bool\Some as BoolSome;
use maarky\Option\Type\Bool\None as BoolNone;

class SomeTest extends TestCase
{
    public function testGet()
    {
        $value = 1;
        $some = new Some($value);
        $this->assertEquals($value, $some->get());
    }

    public function testGetOrElse()
    {
        $value = 1;
        $some = new Some($value);
        $this->assertEquals($value, $some->getorElse(2));
    }

    public function testGetOrCall()
    {
        $value = 1;
        $some = new Some($value);
        $this->assertEquals($value, $some->getorCall(function() { return 5; }));
    }

    public function testGetOrElse_notElse()
    {
        $value = 1;
        $else = 2;
        $some = new Some($value);
        $this->assertNotEquals($else, $some->getorElse($else));
    }

    public function testFlatMap()
    {
        $value = 2;
        $double = function(int $num, Some $some) { return new Some($num * 2); };
        $some = new Some($value);
        $expected = $double($value, $some);
        $this->assertEquals($expected, $some->flatMap($double));
    }

    public function testFlatMap_mustReturnProperSome()
    {
        $expected = new BoolSome(true);
        $function = function() use($expected) { return $expected; };
        $some = new Some(5);
        $this->assertEquals($expected, $some->flatMap($function));
    }

    public function testFlatMap_mustReturnProperNone()
    {
        $expected = new BoolNone();
        $function = function() use($expected) { return $expected; };
        $some = new Some(5);
        $this->assertEquals($expected, $some->flatMap($function));
    }

    public function testFlatMap_mustNotReturnOptionWithOption()
    {
        $value = 2;
        $mapValue = 4;
        $option = function(int $num) use($mapValue) { return new Some($mapValue); };
        $expected = new Some($mapValue);
        $some = new Some($value);
        $this->assertEquals($expected, $some->flatMap($option));
    }

    public function testFlatMap_returnsNone()
    {
        $none = new None;
        $option = function() use($none) { return $none; };
        $some = new Some(2);
        $this->assertEquals($none, $some->flatMap($option));
    }

    public function testFlatMap_withSomeNone()
    {
        $expected = new Some(new None);
        $map = function() use($expected) { return $expected; };
        $some = new Some(2);
        $this->assertEquals($expected, $some->flatMap($map));
    }

    public function testFlatMap_callbackMustReturnOption()
    {
        $this->expectException('TypeError');
        $some = new Some(2);
        $some->flatMap(function() { return 1; });
    }

    public function testMap()
    {
        $value = 2;
        $double = function(int $num) { return $num * 2; };
        $expected = new Some($double($value));
        $some = new Some($value);
        $this->assertEquals($expected, $some->map($double));
    }

    public function testMap_callbackString()
    {
        $value = 'hello';
        $some = new Some($value);
        $expected = new Some(strtolower($value));
        $this->assertEquals($expected, $some->map('strtolower'));
    }

    public function testMap_mayReturnOptionWithSome()
    {
        $value = 2;
        $mapped = new Some(4);
        $map = function() use($mapped) { return $mapped; };
        $expected = new Some($mapped);
        $some = new Some($value);
        $this->assertEquals($expected, $some->map($map));
    }

    public function testMap_mayReturnOptionWithNone()
    {
        $value = 2;
        $mapped = new None;
        $map = function() use($mapped) { return $mapped; };
        $expected = new Some($mapped);
        $some = new Some($value);
        $this->assertEquals($expected, $some->map($map));
    }

    public function testFilter()
    {
        $filter = function() { return true; };
        $some = new Some(2);
        $this->assertEquals($some, $some->filter($filter));
    }

    public function testFilter_false()
    {
        $filter = function() { return false; };
        $some = new Some(2);
        $this->assertEquals(new None, $some->filter($filter));
    }

    public function testFilter_callbackArray_true()
    {
        $some = new Some(1);
        $this->assertEquals($some, $some->filter(array($this, 'callbackTrue')));
    }

    public function testFilter_callbackArray_false()
    {
        $some = new Some(1);
        $this->assertInstanceOf('maarky\Option\None', $some->filter(array($this, 'callbackFalse')));
    }

    public function testFilterNot()
    {
        $filter = function() { return false; };
        $some = new Some(2);
        $this->assertEquals($some, $some->filterNot($filter));
    }

    public function testFilterNot_true()
    {
        $filter = function() { return true; };
        $some = new Some(2);
        $this->assertEquals(new None, $some->filterNot($filter));
    }

    public function testForEach()
    {
        $some = new Some(1);
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
        $some = new Some(1);
        $callable = new class() {
            public $called = false;

            public function __invoke()
            {
                $this->called = true;
            }
        };
        $some->fornone($callable);
        $this->assertFalse($callable->called);
    }

    public function testOrElse()
    {
        $some = new Some(1);
        $else = new Some(2);
        $this->assertSame($some, $some->orElse($else));
    }

    public function testOrCall()
    {
        $some = new Some(1);
        $this->assertSame($some, $some->orCall(function(){}));
    }

    public function testIsDefined()
    {
        $some = new Some(1);
        $this->assertTrue($some->isDefined());
    }

    public function testIsEmpty()
    {
        $some = new Some(1);
        $this->assertFalse($some->isEmpty());
    }

    public function testEquals()
    {
        $some = new Some(1);
        $this->assertTrue($some->equals($some));
    }

    public function testEquals_false()
    {
        $some = new Some(1);
        $other = new Some(2);
        $this->assertFalse($some->equals($other));
    }

    public function testEquals_false_typeCheck()
    {
        $some = new Some(1);
        $other = new Some('1');
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