<?php


namespace App\Tests;


use App\Traits\StringParsing;
use PHPUnit\Framework\TestCase;

class StringParsingTest extends TestCase
{
    use StringParsing;

    public function testStringParsing(){
        $firstCase = $this->stringParsing("Gran Turismo Série5");

        $this->assertTrue(in_array("Série 5", $firstCase));

        $secondCase = $this->stringParsing("ds 3 crossback");

        $this->assertTrue(in_array("ds3", $secondCase));

        $thirdCase = $this->stringParsing("CrossBack ds 3");

        $this->assertTrue(in_array("ds3", $thirdCase));

        $forthCase = $this->stringParsing("rs4 avant");
        $this->assertTrue(in_array("rs4", $forthCase));
    }
}