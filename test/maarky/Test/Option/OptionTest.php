<?php


namespace maarky\Test\Option;

use PHPUnit\Framework\TestCase;
use maarky\Option\Option;
use maarky\Option\Component\BaseSome;
use maarky\Option\Component\BaseNone;
use maarky\Option\Some;
use maarky\Option\None;


class OptionTest extends TestCase
{
    public function testNew_Some()
    {
        $this->assertInstanceOf('maarky\Option\Some', Option::new(1));
    }

    public function testNew_Some_Filter()
    {
        $value = 1;
        $this->assertInstanceOf('maarky\Option\Some', Option::new($value, function($val) use($value) { return $val === $value; }));
    }

    public function testNew_Some_FilterIsCalled()
    {
        $value = 1;
        Option::new($value, function($val) use($value) { $this->assertEquals($value, $val);return true; });
    }

    public function testNew_None()
    {
        $this->assertInstanceOf('maarky\Option\None', Option::new(null));
    }

    public function testNew_None_Filter()
    {
        $value = 1;
        $this->assertInstanceOf('maarky\Option\None', Option::new($value, function($val) use($value) { return $val !== $value; }));
    }
}
