<?php
declare(strict_types=1);

namespace maarky\Test\Option;

use maarky\Option\None;
use maarky\Option\Some;

class SomeTest extends \PHPUnit_Framework_TestCase
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
        $double = function(int $num) { return $num * 2; };
        $expected = new Some($double($value));
        $some = new Some($value);
        $this->assertEquals($expected, $some->flatMap($double));
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
        $none = new None;
        $option = function() use($none) { return new Some($none); };
        $some = new Some(2);
        $this->assertEquals($none, $some->flatMap($option));
    }

    public function testMap()
    {
        $value = 2;
        $double = function(int $num) { return $num * 2; };
        $expected = new Some($double($value));
        $some = new Some($value);
        $this->assertEquals($expected, $some->map($double));
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
        $this->assertEquals($some, $some->filter(array($this, 'callbackT')));
    }

    public function testFilter_callbackArray_false()
    {
        $some = new Some(1);
        $this->assertInstanceOf('maarky\Option\None', $some->filter(array($this, 'callbackF')));
    }

    public function testForEach()
    {
        $value = 'echo';
        $some = new Some($value);
        $func = function($value) { echo $value; };

        ob_start();
        $some->foreach($func);
        $forEach = ob_get_contents();
        ob_end_clean();
        $this->assertEquals($value, $forEach);
    }

    public function testOrElse()
    {
        $some = new Some(1);
        $else = new Some(2);
        $this->assertSame($some, $some->orElse($else));
    }

    public function testOrElseCall()
    {
        $some = new Some(1);
        $this->assertSame($some, $some->orElseCall(function(){}));
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

    public function callbackT()
    {
        return true;
    }

    public function callbackF()
    {
        return false;
    }
}