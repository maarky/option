<?php
declare(strict_types=1);

namespace maarky\Test\Option\Type\Callback;

use PHPUnit\Framework\TestCase;
use maarky\Option\Type\Callback\Some;
use maarky\Option\Type\Callback\None;
use maarky\Option\Some as BaseSome;

class SomeTest extends TestCase
{
    public function testSome()
    {
        $this->assertInstanceOf('maarky\Option\Type\Callback\Some', new Some(function(){}));
    }
    public function testSome_withInteger()
    {
        $this->expectException('TypeError');
        $this->assertInstanceOf('maarky\Option\Type\Callback\Some', new Some(1));
    }

    public function testSome_wrongType()
    {
        $this->expectException('TypeError');
        $this->assertInstanceOf('maarky\Option\Type\Callback\Some', new Some('a'));
    }

    public function testSome_withNull()
    {
        $this->expectException('TypeError');
        $this->assertInstanceOf('maarky\Option\Type\Callback\Some', new Some(null));
    }

    public function testFilter()
    {
        $some = new Some(function(){});
        $filter = function() { return true; };
        $this->assertEquals($some, $some->filter($filter));
    }

    public function testFilter_false()
    {
        $some = new Some(function(){});
        $none = new None;
        $filter = function() { return false; };
        $this->assertEquals($none, $some->filter($filter));
    }

    public function testEquals()
    {
        $callback = function(){};
        $some = new Some($callback);
        $equal = new Some($callback);
        $this->assertTrue($some->equals($equal));
    }

    public function testEquals_false()
    {
        $callback = function(){};
        $callback2 = function(){};
        $some = new Some($callback);
        $unequal = new Some($callback2);
        $this->assertFalse($some->equals($unequal));
    }

    public function testEquals_false_wrongSome()
    {
        $callback = function(){};
        $some = new Some($callback);
        $baseSome = new BaseSome($callback);
        $this->assertFalse($some->equals($baseSome));
    }

    public function testEquals_false_withNone()
    {
        $some = new Some(function(){});
        $this->assertFalse($some->equals(new None()));
    }

    public function testOrElse()
    {
        $some = new Some(function(){});
        $else = new Some(function(){ return 1; });
        $this->assertEquals($some, $some->orElse($else));
    }

    public function testOrElse_none()
    {
        $some = new Some(function(){});
        $else = new None();
        $this->assertEquals($some, $some->orElse($else));
    }
}