<?php
declare(strict_types=1);

namespace maarky\Test\Option\Type\Bool;

use PHPUnit\Framework\TestCase;
use maarky\Option\Type\Bool\Some;
use maarky\Option\Type\Bool\None;
use maarky\Option\Some as BaseSome;

class SomeTest extends TestCase
{
    public function testSome()
    {
        $this->assertInstanceOf('maarky\Option\Type\Bool\Some', new Some(true));
    }

    public function testSome_withInteger()
    {
        $this->expectException('TypeError');
        $this->assertInstanceOf('maarky\Option\Type\Bool\Some', new Some(1));
    }

    public function testSome_wrongType()
    {
        $this->expectException('TypeError');
        $this->assertInstanceOf('maarky\Option\Type\Bool\Some', new Some('a'));
    }

    public function testSome_withNull()
    {
        $this->expectException('TypeError');
        $this->assertInstanceOf('maarky\Option\Type\Bool\Some', new Some(null));
    }

    public function testFilter()
    {
        $some = new Some(true);
        $filter = function() { return true; };
        $this->assertEquals($some, $some->filter($filter));
    }

    public function testFilter_false()
    {
        $some = new Some(true);
        $none = new None;
        $filter = function() { return false; };
        $this->assertEquals($none, $some->filter($filter));
    }

    public function testEquals()
    {
        $some = new Some(true);
        $this->assertTrue($some->equals($some));
    }

    public function testEquals_false()
    {
        $some = new Some(true);
        $unequal = new Some(false);
        $this->assertFalse($some->equals($unequal));
    }

    public function testEquals_false_wrongSome()
    {
        $some = new Some(true);
        $baseSome = new BaseSome(true);
        $this->assertFalse($some->equals($baseSome));
    }

    public function testEquals_false_withNone()
    {
        $some = new Some(true);
        $this->assertFalse($some->equals(new None()));
    }

    public function testOrElse()
    {
        $some = new Some(true);
        $else = new Some(false);
        $this->assertEquals($some, $some->orElse($else));
    }

    public function testOrElse_none()
    {
        $some = new Some(true);
        $else = new None();
        $this->assertEquals($some, $some->orElse($else));
    }
}