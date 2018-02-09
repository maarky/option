<?php
declare(strict_types=1);

namespace maarky\Test\Option\Type;

use PHPUnit\Framework\TestCase;
use maarky\Option\Type\Callback\Option;

class CallbackTest extends TestCase
{
    public function testSome()
    {
        $option = Option::new(function(){});
        $this->assertTrue($option->isDefined());
    }
    public function testSome_get()
    {
        $val = 'strpos';
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
        $val = function(){};
        $this->assertSame($val, Option::new(1)->getOrElse($val));
    }

    public function testNone_getOrElse_badElse()
    {
        $this->expectException(\TypeError::class);
        Option::new(1)->getOrElse(1);
    }

    public function testNone_getOrCall()
    {
        $val = [$this, 'testNone_getOrCall'];
        $this->assertSame($val, Option::new(1)->getOrCall(function() use ($val) {return $val;}));
    }

    public function testNone_getOrCall_badCall()
    {
        $this->expectException(\TypeError::class);
        Option::new(1)->getOrCall(function(){return 1;});
    }
}