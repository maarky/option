<?php


namespace maarky\Test\Option;

use PHPUnit\Framework\TestCase;
use maarky\Option\Option;


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

    public function testNew_None()
    {
        $this->assertInstanceOf('maarky\Option\None', Option::new(null));
    }

    public function testNew_None_Filter()
    {
        $value = 1;
        $this->assertInstanceOf('maarky\Option\None', Option::new($value, function($val) use($value) { return $val !== $value; }));
    }

    public function testNew_Some_TypeInt()
    {
        $this->assertInstanceOf('maarky\Option\Type\Int\Some', \maarky\Option\Type\Int\Option::new(1));
    }

    public function testNew_None_TypeInt()
    {
        $this->assertInstanceOf('maarky\Option\Type\Int\None', \maarky\Option\Type\Int\Option::new('1'));
    }
}
