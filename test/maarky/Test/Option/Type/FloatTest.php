<?php
declare(strict_types=1);

namespace maarky\Test\Option\Type;

use PHPUnit\Framework\TestCase;
use maarky\Option\Type\Float\Option;

class FloatTest extends TestCase
{
    public function testSome()
    {
        $option = Option::new(1.0);
        $this->assertTrue($option->isDefined());
    }
    public function testSome_get()
    {
        $val = 1.0;
        $option = Option::new($val);
        $this->assertSame($val, $option->get());
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
        $val = 0.0;
        $this->assertSame($val, Option::new(1)->getOrElse($val));
    }

    public function testNone_getOrElse_badElse()
    {
        $this->expectException(\TypeError::class);
        Option::new(1)->getOrElse(1);
    }

    public function testNone_getOrCall()
    {
        $val = 0.1;
        $this->assertSame($val, Option::new(1)->getOrCall(function() use ($val) {return $val;}));
    }

    public function testNone_getOrCall_badCall()
    {
        $this->expectException(\TypeError::class);
        Option::new(1)->getOrCall(function(){return 1;});
    }
}