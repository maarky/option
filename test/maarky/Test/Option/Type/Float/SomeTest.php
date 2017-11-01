<?php
declare(strict_types=1);

namespace maarky\Test\Option\Type\Float;

use PHPUnit\Framework\TestCase;
use maarky\Option\Type\Float\Some;
use maarky\Option\Type\Float\None;
use maarky\Option\Some as BaseSome;

class SomeTest extends TestCase
{
    public function testSome()
    {
        $this->assertInstanceOf('maarky\Option\Type\Float\Some', new Some(1.0));
    }
    public function testSome_withInteger()
    {
        $this->expectException('TypeError');
        $this->assertInstanceOf('maarky\Option\Type\Float\Some', new Some(1));
    }

    public function testSome_wrongType()
    {
        $this->expectException('TypeError');
        $this->assertInstanceOf('maarky\Option\Type\Float\Some', new Some('a'));
    }

    public function testSome_withNull()
    {
        $this->expectException('TypeError');
        $this->assertInstanceOf('maarky\Option\Type\Float\Some', new Some(null));
    }

    public function testFilter()
    {
        $some = new Some(1.0);
        $filter = function() { return true; };
        $this->assertEquals($some, $some->filter($filter));
    }

    public function testFilter_false()
    {
        $some = new Some(1.0);
        $none = new None;
        $filter = function() { return false; };
        $this->assertEquals($none, $some->filter($filter));
    }

    public function testEquals()
    {
        $some = new Some(1.0);
        $this->assertTrue($some->equals($some));
    }

    public function testEquals_false()
    {
        $some = new Some(1.0);
        $unequal = new Some(2.0);
        $this->assertFalse($some->equals($unequal));
    }

    public function testEquals_false_wrongSome()
    {
        $some = new Some(1.0);
        $baseSome = new BaseSome(1);
        $this->assertFalse($some->equals($baseSome));
    }

    public function testEquals_false_withNone()
    {
        $some = new Some(1.0);
        $this->assertFalse($some->equals(new None()));
    }

    public function testOrElse()
    {
        $some = new Some(1.0);
        $else = new Some(2.0);
        $this->assertEquals($some, $some->orElse($else));
    }

    public function testOrElse_none()
    {
        $some = new Some(1.0);
        $else = new None();
        $this->assertEquals($some, $some->orElse($else));
    }
}