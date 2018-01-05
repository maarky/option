<?php
declare(strict_types=1);

namespace maarky\Test\Option;

use PHPUnit\Framework\TestCase;
use maarky\Option\None;
use maarky\Option\Some;

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

    public function testForNone()
    {
        $none = new None;
        $callable = new class() {
            public $called = false;

            public function __invoke(None $none)
            {
                $this->called = true;
            }
        };
        $none->fornone($callable);
        $this->assertTrue($callable->called);
    }

    public function testGet()
    {
        $none = new None;
        $this->assertEquals($none, $none->get());
    }

    public function testGetOrElse()
    {
        $none = new None;
        $else = 1;
        $this->assertEquals($else, $none->getOrElse($else));
    }

    public function testGetOrCall()
    {
        $none = new None;
        $else = 1;
        $this->assertEquals($else, $none->getOrCall(function(None $none) use($else) { return $else; }));
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
        $some = new Some(1);
        $this->assertEquals($some, $none->orElse($some));
    }

    public function testOrCall()
    {
        $none = new None;
        $some = new Some(1);
        $this->assertSame($some, $none->orCall(function(None $none) use($some) { return $some; }));
    }

    public function testEquals()
    {
        $none = new None;
        $this->assertTrue($none->equals($none));
    }

    public function testEquals_false()
    {
        $none = new None;
        $some = new Some(1);
        $this->assertFalse($none->equals($some));
    }
}