<?php
declare(strict_types=1);

namespace maarky\Test\Option;

use PHPUnit\Framework\TestCase;
use maarky\Option\None;
use maarky\Option\Option;

class NoneTest extends TestCase
{
    public function testFilter()
    {
        $none = Option::new(null);
        $this->assertEquals($none, $none->filter(function(){ return true; }));
    }

    public function testFlatMap()
    {
        $none = Option::new(null);
        $this->assertEquals($none, $none->flatMap(function(){ return true; }));
    }

    public function testForeach_doesNotCallFunction()
    {
        $none = Option::new(null);
        try {
            $none->foreach(function(){ throw new \Exception; });
            $this->assertTrue(true);
        } catch(\Exception $e) {
            $this->assertTrue(false);
        }
    }

    public function testForNothing()
    {
        $none = Option::new(null);
        $callable = new class() {
            public $called = false;

            public function __invoke(None $none)
            {
                $this->called = true;
            }
        };
        $none->fornothing($callable);
        $this->assertTrue($callable->called);
    }

    public function testGet()
    {
        $this->expectException(\TypeError::class);
        $none = Option::new(null);
        $none->get();
    }

    public function testGetOrElse()
    {
        $none = Option::new(null);
        $else = 1;
        $this->assertEquals($else, $none->getOrElse($else));
    }

    public function testGetOrCall()
    {
        $none = Option::new(null);
        $else = 1;
        $this->assertEquals($else, $none->getOrCall(function(None $none) use($else) { return $else; }));
    }

    public function testIsDefined()
    {
        $none = Option::new(null);
        $this->assertFalse($none->isDefined());
    }

    public function testIsEmpty()
    {
        $none = Option::new(null);
        $this->assertTrue($none->isEmpty());
    }

    public function testMap()
    {
        $none = Option::new(null);
        $this->assertEquals($none, $none->map(function(){ return true; }));
    }

    public function testOrElse()
    {
        $none = Option::new(null);
        $some = Option::new(1);
        $this->assertEquals($some, $none->orElse($some));
    }

    public function testOrCall()
    {
        $none = Option::new(null);
        $some = Option::new(1);
        $this->assertSame($some, $none->orCall(function(None $none) use($some) { return $some; }));
    }

    public function testEquals()
    {
        $none = Option::new(null);
        $this->assertTrue($none->equals($none));
    }

    public function testEquals_false()
    {
        $none = Option::new(null);
        $some = Option::new(1);
        $this->assertFalse($none->equals($some));
    }
}