<?php
declare(strict_types=1);

namespace maarky\Test\Option\Type\Null;

use PHPUnit\Framework\TestCase;
use maarky\Option\Type\Null\None;
use maarky\Option\Type\Null\Some;

class NoneTest extends TestCase
{
    public function testFilter()
    {
        $none = new None;
        $this->assertEquals($none, $none->filter(function(){ return true; }));
    }

    public function testFlatMap()
    {
        $none = new None;
        $this->assertEquals($none, $none->flatMap(function(){ return true; }));
    }

    public function testForeach_doesNotCallFunction()
    {
        $none = new None;
        try {
            $none->foreach(function(){ throw new \Exception; });
            $this->assertTrue(true);
        } catch(\Exception $e) {
            $this->assertTrue(false);
        }
    }

    public function testGet()
    {
        $none = new None;
        $this->assertEquals($none, $none->get());
    }

    public function testGetOrElse()
    {
        $none = new None;
        $else = null;
        $this->assertEquals($else, $none->getOrElse($else));
    }

    public function testIsDefined()
    {
        $none = new None;
        $this->assertFalse($none->isDefined());
    }

    public function testIsEmpty()
    {
        $none = new None;
        $this->assertTrue($none->isEmpty());
    }

    public function testMap()
    {
        $none = new None;
        $this->assertEquals($none, $none->map(function(){ return true; }));
    }

    public function testOrElse()
    {
        $none = new None;
        $some = new Some(null);
        $this->assertEquals($some, $none->orElse($some));
    }

    public function testEquals()
    {
        $none = new None;
        $this->assertTrue($none->equals($none));
    }

    public function testEquals_false()
    {
        $none = new None;
        $some = new Some(null);
        $this->assertFalse($none->equals($some));
    }
}