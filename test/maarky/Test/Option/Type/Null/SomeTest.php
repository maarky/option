<?php
declare(strict_types=1);

namespace maarky\Test\Option\Type\Null;

use PHPUnit\Framework\TestCase;
use maarky\Option\Type\Null\Some;
use maarky\Option\Type\Null\None;

class SomeTest extends TestCase
{
    public function testSome()
    {
        $this->assertInstanceOf('maarky\Option\Type\Null\Some', new Some(null));
    }
    public function testSome_withInteger()
    {
        $this->expectException('TypeError');
        $this->assertInstanceOf('maarky\Option\Type\Null\Some', new Some(1));
    }

    public function testSome_wrongType()
    {
        $this->expectException('TypeError');
        $this->assertInstanceOf('maarky\Option\Type\Null\Some', new Some('a'));
    }

    public function testSome_withEmptyString()
    {
        $this->expectException('TypeError');
        $this->assertInstanceOf('maarky\Option\Type\Null\Some', new Some(''));
    }

    public function testFilter()
    {
        $some = new Some(null);
        $filter = function() { return true; };
        $this->assertEquals($some, $some->filter($filter));
    }

    public function testFilter_false()
    {
        $some = new Some(null);
        $none = new None;
        $filter = function() { return false; };
        $this->assertEquals($none, $some->filter($filter));
    }

    public function testEquals()
    {
        $some = new Some(null);
        $this->assertTrue($some->equals($some));
    }

    public function testEquals_false_withNone()
    {
        $some = new Some(null);
        $this->assertFalse($some->equals(new None()));
    }

    public function testOrElse()
    {
        $some = new Some(null);
        $else = new Some(null);
        $this->assertEquals($some, $some->orElse($else));
    }

    public function testOrElse_none()
    {
        $some = new Some(null);
        $else = new None();
        $this->assertEquals($some, $some->orElse($else));
    }
}