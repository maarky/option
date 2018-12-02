<?php
declare(strict_types=1);

namespace maarky\Test\Option\Type;

use PHPUnit\Framework\TestCase;
use maarky\Option\Type\Int\Option;

class IntTest extends TestCase
{
    public function testSome()
    {
        $option = Option::new(1);
        $this->assertTrue($option->isDefined());
    }
    public function testSome_get()
    {
        $val = 1;
        $option = Option::new($val);
        $this->assertSame($val, $option->get());
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
        $val = 1;
        $this->assertSame($val, Option::new(0.0)->getOrElse($val));
    }

    public function testNone_getOrElse_badElse()
    {
        $value = 1.0;
        $actual = Option::new($value)->getOrElse($value);
        $this->assertEquals($value, $actual);
    }

    public function testNone_getOrCall()
    {
        $val = 1;
        $this->assertSame($val, Option::new(1.0)->getOrCall(function() use ($val) {return $val;}));
    }

    public function testNone_getOrCall_badCall()
    {
        $value = 1.0;
        $actual = Option::new($value)->getOrCall(function()use($value){return $value;});
        $this->assertEquals($value, $actual);
    }
}