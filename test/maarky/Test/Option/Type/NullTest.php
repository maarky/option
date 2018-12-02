<?php
declare(strict_types=1);

namespace maarky\Test\Option\Type;

use PHPUnit\Framework\TestCase;
use maarky\Option\Type\Null\Option;

class NullTest extends TestCase
{
    public function testSome()
    {
        $option = Option::new(null);
        $this->assertTrue($option->isDefined());
    }
    public function testSome_get()
    {
        $val = null;
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
        $val = null;
        $this->assertSame($val, Option::new(0.0)->getOrElse($val));
    }

    public function testNone_getOrCall()
    {
        $val = null;
        $this->assertSame($val, Option::new(1.0)->getOrCall(function() use ($val) {return $val;}));
    }
}