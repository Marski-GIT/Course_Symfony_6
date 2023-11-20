<?php

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\TestCalculator;

class ExampleUnitTest extends TestCase
{
    public function testSomething(): void
    {
        $this->assertTrue(true);
        $this->assertFalse(1 == '10');
    }

    public function testContains(): void
    {
        $this->assertContains(4, [1, 2, 3, 4, 5]);
    }

    public function testCount(): void
    {
        $this->assertCount(1, [1]);
    }

    public function testEmpty(): void
    {
        $this->assertEmpty([]);
    }

    public function testEquals1(): void
    {
        $this->assertEquals(1, 1);
    }

    public function testEquals2(): void
    {
        $this->assertEquals('bar', 'bar');
    }

    public function testEquals3(): void
    {
        $this->assertEquals(['a', 'b', 'c'], ['a', 'b', 'c']);
    }

    public function testEquals4(): void
    {
        $expected = new \stdClass();
        $expected->foo = 'foo';
        $expected->bar = 'bar';

        $actual = new \stdClass();
        $actual->foo = 'foo';
        $actual->bar = 'bar';

        $this->assertEquals($expected, $actual);
    }

    /**
     * Pierwsza wartość mniejsza od drugiej.
     * @return void
     */
    public function testGreaterThan(): void
    {
        $this->assertGreaterThan(1, 2);
    }

    /**
     * Pierwsza wartość większa od drugiej.
     * @return void
     */
    public function testLessThan(): void
    {
        $this->assertLessThan(2, 1);
    }

    public function testAdd(): void
    {
        $calc = new TestCalculator();
        $this->assertEquals(2, $calc->add(1, 1));
    }
}
