<?php
declare(strict_types=1);

namespace maarky\Test\Option\Type\Arr;

use maarky\Option\Type\Arr\Some;
use maarky\Option\Type\Arr\None;
use maarky\Option\Some as BaseSome;

class SomeTest extends \PHPUnit_Framework_TestCase
{
    public function testSome()
    {
        new Some(['a']);
    }

    public function testSome_wrongType()
    {
        $this->setExpectedException('TypeError');
        new Some(1);
    }

    public function testSome_withNull()
    {
        $this->setExpectedException('TypeError');
        new Some(null);
    }

    public function testFilter()
    {
        $some = new Some(['a']);
        $filter = function() { return true; };
        $this->assertEquals($some, $some->filter($filter));
    }

    public function testFilter_false()
    {
        $some = new Some(['a']);
        $none = new None;
        $filter = function() { return false; };
        $this->assertEquals($none, $some->filter($filter));
    }

    public function testEquals()
    {
        $some = new Some(['a']);
        $this->assertTrue($some->equals($some));
    }

    public function testEquals_false()
    {
        $some = new Some(['a']);
        $unequal = new Some(['aa']);
        $this->assertFalse($some->equals($unequal));
    }

    public function testEquals_false_wrongSome()
    {
        $some = new Some(['a']);
        $baseSome = new BaseSome(['a']);
        $this->assertFalse($some->equals($baseSome));
    }

    public function testEquals_false_withNone()
    {
        $some = new Some(['a']);
        $this->assertFalse($some->equals(new None()));
    }

    public function testOrElse()
    {
        $some = new Some(['a']);
        $else = new Some(['e']);
        $this->assertEquals($some, $some->orElse($else));
    }

    public function testOrElse_none()
    {
        $some = new Some(['a']);
        $else = new None();
        $this->assertEquals($some, $some->orElse($else));
    }
}