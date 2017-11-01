<?php
declare(strict_types=1);

namespace maarky\Test\Option\Type\DateTime;

use PHPUnit\Framework\TestCase;
use stdClass;
use DateTime;
use maarky\Option\Type\DateTime\Some;
use maarky\Option\Type\DateTime\None;
use maarky\Option\Some as BaseSome;

class SomeTest extends TestCase
{
    public function testSome()
    {
        $this->assertInstanceOf('maarky\Option\Type\DateTime\Some', new Some(new DateTime));
    }
    public function testSome_withInteger()
    {
        $this->expectException('TypeError');
        $this->assertInstanceOf('maarky\Option\Type\DateTime\Some', new Some(1));
    }

    public function testSome_wrongType()
    {
        $this->expectException('TypeError');
        $this->assertInstanceOf('maarky\Option\Type\DateTime\Some', new Some('a'));
    }

    public function testSome_wrongTypeIsObject()
    {
        $this->expectException('TypeError');
        $this->assertInstanceOf('maarky\Option\Type\DateTime\Some', new Some(new stdClass));
    }

    public function testSome_withNull()
    {
        $this->expectException('TypeError');
        $this->assertInstanceOf('maarky\Option\Type\DateTime\Some', new Some(null));
    }

    public function testFilter()
    {
        $some = new Some(new DateTime);
        $filter = function() { return true; };
        $this->assertEquals($some, $some->filter($filter));
    }

    public function testFilter_false()
    {
        $some = new Some(new DateTime);
        $none = new None;
        $filter = function() { return false; };
        $this->assertEquals($none, $some->filter($filter));
    }

    public function testEquals()
    {
        $some = new Some(new DateTime);
        $this->assertTrue($some->equals($some));
    }

    public function testEquals_false()
    {
        $some = new Some(new DateTime);
        $stdClass = new DateTime('yesterday');
        $stdClass->a = 1;
        $unequal = new Some($stdClass);
        $this->assertFalse($some->equals($unequal));
    }

    public function testEquals_false_wrongSome()
    {
        $some = new Some(new DateTime);
        $baseSome = new BaseSome(new DateTime);
        $this->assertFalse($some->equals($baseSome));
    }

    public function testEquals_false_withNone()
    {
        $some = new Some(new DateTime);
        $this->assertFalse($some->equals(new None()));
    }

    public function testOrElse()
    {
        $some = new Some(new DateTime);
        $dateTime = new DateTime('yesterday');
        $else = new Some($dateTime);
        $this->assertEquals($some, $some->orElse($else));
    }

    public function testOrElse_none()
    {
        $some = new Some(new DateTime);
        $else = new None();
        $this->assertEquals($some, $some->orElse($else));
    }
}