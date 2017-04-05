<?php


namespace maarky\Test\Option;

use maarky\Option\Option;


class OptionTest extends \PHPUnit_Framework_TestCase
{
    public function testNew_Some()
    {
        $this->assertInstanceOf('maarky\Option\Some', Option::new(1));
    }

    public function testNew_None()
    {
        $this->assertInstanceOf('maarky\Option\None', Option::new(null));
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
