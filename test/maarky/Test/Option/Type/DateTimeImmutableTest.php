<?php
declare(strict_types=1);

namespace maarky\Test\Option\Type;

use PHPUnit\Framework\TestCase;
use maarky\Option\Type\PHP\Classes\DateTimeImmutable\Option;

class DateTimeImmutableTest extends TestCase
{
    public function testSome()
    {
        $option = Option::create(new \DateTimeImmutable());
        $this->assertTrue($option->isDefined());
    }

    public function testSome_get()
    {
        $val = new \DateTimeImmutable();
        $option = Option::create($val);
        $this->assertEquals($val, $option->get());
    }

    public function testNone()
    {
        $option = Option::create(1.0);
        $this->assertTrue($option->isEmpty());
    }

    public function testNone_get()
    {
        $this->expectException(\TypeError::class);
        Option::create(1.0)->get();
    }

    public function testNone_getOrElse()
    {
        $val = new \DateTimeImmutable();
        $this->assertSame($val, Option::create(0.0)->getOrElse($val));
    }

    public function testNone_getOrCall()
    {
        $val = new \DateTimeImmutable();
        $this->assertSame($val, Option::create(1.0)->getOrCall(function() use ($val) {return $val;}));
    }
}