<?php
declare(strict_types=1);

namespace maarky\Test\Option\Type;

use PHPUnit\Framework\TestCase;
use maarky\Option\Type\Bool\Option;

class BoolTest extends TestCase
{
    public function testSome()
    {
        $option = Option::new(true);
        $this->assertTrue($option->isDefined());
    }
    public function testSome_get()
    {
        $val = false;
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
        $val = true;
        $this->assertSame($val, Option::new(1)->getOrElse($val));
    }

    public function testNone_getOrElse_badElse()
    {
        $value = 1;
        $actual = Option::new($value)->getOrElse($value);
        $this->assertEquals($value, $actual);
    }

    public function testNone_getOrCall()
    {
        $val = false;
        $this->assertSame($val, Option::new(1)->getOrCall(function() use ($val) {return $val;}));
    }

    public function testNone_getOrCall_badCall()
    {
        $value = 1;
        $actual = Option::new($value)->getOrCall(function()use($value){return $value;});
        $this->assertEquals($value, $actual);
    }
}