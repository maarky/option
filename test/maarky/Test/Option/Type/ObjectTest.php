<?php
declare(strict_types=1);

namespace maarky\Test\Option\Type;

use PHPUnit\Framework\TestCase;
use maarky\Option\Type\Object\Option;

class ObjectTest extends TestCase
{
    public function testSome()
    {
        $option = Option::new(new class(){});
        $this->assertTrue($option->isDefined());
    }

    public function testSome_get()
    {
        $val = new class(){};
        $option = Option::new($val);
        $this->assertEquals($val, $option->get());
    }

    public function testNone()
    {
        $option = Option::new(1.0);
        $this->assertTrue($option->isEmpty());
    }

    public function testNone_get()
    {
        $this->expectException(\TypeError::class);
        Option::new(1.0)->get();
    }

    public function testNone_getOrElse()
    {
        $val = new class(){};
        $this->assertSame($val, Option::new(0.0)->getOrElse($val));
    }

    public function testNone_getOrCall()
    {
        $val = new class(){};
        $this->assertSame($val, Option::new(1.0)->getOrCall(function() use ($val) {return $val;}));
    }
}