<?php

namespace Tests\AppBundle\Entity;

use PHPUnit\Framework\TestCase;
use AppBundle\Form\DataTransformer\StringToArrayTransformer;

class StringToArrayTransformerTest extends TestCase
{
    public function testReverseAndTransform()
    {
        $transformer = new StringToArrayTransformer();

        $array = ['value 1', 'value 2', 'value 3'];

        $this->assertEquals("value 1", $transformer->transform($array));

        $value = "my value";

        $this->assertEquals([$value], $transformer->reverseTransform($value));
    }
}
