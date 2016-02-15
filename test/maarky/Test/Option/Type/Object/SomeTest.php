<?php
declare(strict_types=1);

namespace maarky\Test\Option\Type\Object;

use stdClass;
use maarky\Option\Type\Object\Some;
use maarky\Option\Type\Object\None;
use maarky\Option\Some as BaseSome;

class SomeTest extends \PHPUnit_Framework_TestCase
{
    public function testSome()
    {
        new Some(new StdClass);
    }

    public function testSome_withDateTime()
    {
        new Some(new \DateTime);
    }

    public function testSome_withInteger()
    {
        $this->setExpectedException('TypeError');
        new Some(1);
    }

    public function testSome_wrongType()
    {
        $this->setExpectedException('TypeError');
        new Some('a');
    }

    public function testSome_withNull()
    {
        $this->setExpectedException('TypeError');
        new Some(null);
    }

    public function testFilter()
    {
        $some = new Some(new StdClass);
        $filter = function() { return true; };
        $this->assertEquals($some, $some->filter($filter));
    }

    public function testFilter_false()
    {
        $some = new Some(new StdClass);
        $none = new None;
        $filter = function() { return false; };
        $this->assertEquals($none, $some->filter($filter));
    }

    public function testEquals()
    {
        $some = new Some(new StdClass);
        $this->assertTrue($some->equals($some));
    }

    public function testEquals_false()
    {
        $some = new Some(new StdClass);
        $stdClass = new StdClass;
        $stdClass->a = 1;
        $unequal = new Some($stdClass);
        $this->assertFalse($some->equals($unequal));
    }

    public function testEquals_false_wrongSome()
    {
        $some = new Some(new StdClass);
        $baseSome = new BaseSome(1);
        $this->assertFalse($some->equals($baseSome));
    }

    public function testEquals_false_withNone()
    {
        $some = new Some(new StdClass);
        $this->assertFalse($some->equals(new None()));
    }

    public function testOrElse()
    {
        $some = new Some(new StdClass);
        $stdClass = new StdClass;
        $stdClass->a = 1;
        $else = new Some($stdClass);
        $this->assertEquals($some, $some->orElse($else));
    }

    public function testOrElse_none()
    {
        $some = new Some(new StdClass);
        $else = new None();
        $this->assertEquals($some, $some->orElse($else));
    }
}