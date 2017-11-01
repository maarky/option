<?php
declare(strict_types=1);

namespace maarky\Test\Option\Type\Resource;

use PHPUnit\Framework\TestCase;
use maarky\Option\Type\Resource\Some;
use maarky\Option\Type\Resource\None;
use maarky\Option\Some as BaseSome;

class SomeTest extends TestCase
{
    public function testSome()
    {
        $this->assertInstanceOf('maarky\Option\Type\Resource\Some', new Some(fopen(__FILE__, 'r')));
    }

    public function testSome_wrongType()
    {
        $this->expectException('TypeError');
        $this->assertInstanceOf('maarky\Option\Type\Resource\Some', new Some('a'));
    }

    public function testSome_withNull()
    {
        $this->expectException('TypeError');
        $this->assertInstanceOf('maarky\Option\Type\Resource\Some', new Some(null));
    }

    public function testFilter()
    {
        $some = new Some(fopen(__FILE__, 'r'));
        $filter = function() { return true; };
        $this->assertEquals($some, $some->filter($filter));
    }

    public function testFilter_false()
    {
        $some = new Some(fopen(__FILE__, 'r'));
        $none = new None;
        $filter = function() { return false; };
        $this->assertEquals($none, $some->filter($filter));
    }

    public function testEquals()
    {
        $some = new Some(fopen(__FILE__, 'r'));
        $this->assertTrue($some->equals($some));
    }

    public function testEquals_twoResourcesPointingAtSameFile()
    {
        $some1 = new Some(fopen(__FILE__, 'r'));
        $some2 = new Some(fopen(__FILE__, 'r'));
        $this->assertFalse($some1->equals($some2));
    }

    public function testEquals_false()
    {
        $some = new Some(fopen(__FILE__, 'r'));
        $unequal = new Some(fopen(__DIR__ . '/NoneTest.php', 'r'));
        $this->assertFalse($some->equals($unequal));
    }

    public function testEquals_false_wrongSome()
    {
        $some = new Some(fopen(__FILE__, 'r'));
        $baseSome = new BaseSome(fopen(__FILE__, 'r'));
        $this->assertFalse($some->equals($baseSome));
    }

    public function testEquals_false_withNone()
    {
        $some = new Some(fopen(__FILE__, 'r'));
        $this->assertFalse($some->equals(new None()));
    }

    public function testOrElse()
    {
        $some = new Some(fopen(__FILE__, 'r'));
        $else = new Some(fopen(__DIR__ . '/NoneTest.php', 'r'));
        $this->assertEquals($some, $some->orElse($else));
    }

    public function testOrElse_none()
    {
        $some = new Some(fopen(__FILE__, 'r'));
        $else = new None();
        $this->assertEquals($some, $some->orElse($else));
    }
}