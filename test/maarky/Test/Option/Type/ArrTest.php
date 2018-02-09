<?php

namespace maarky\Test\Option\Type;

use PHPUnit\Framework\TestCase;
use maarky\Option\Type\Arr\Option;

class ArrTest extends TestCase
{
    public function testSome()
    {
        $option = Option::new([]);
        $this->assertTrue($option->isDefined());
    }
    public function testSome_get()
    {
        $option = Option::new([]);
        $this->assertSame([], $option->get());
    }

    public function testNone()
    {
        $option = Option::new(1);
        $this->assertTrue($option->isEmpty());
    }

    public function testNone_get()
    {
        $this->expectException(\TypeError::class);
        Option::new(1)->get();
    }

    public function testNone_getOrElse()
    {
        $this->assertSame([], Option::new(1)->getOrElse([]));
    }

    public function testNone_getOrElse_badElse()
    {
        $this->expectException(\TypeError::class);
        Option::new(1)->getOrElse(1);
    }

    public function testNone_getOrCall()
    {
        $this->assertSame([], Option::new(1)->getOrCall(function(){return [];}));
    }

    public function testNone_getOrCall_badCall()
    {
        $this->expectException(\TypeError::class);
        Option::new(1)->getOrCall(function(){return 1;});
    }
}