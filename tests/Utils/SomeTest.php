<?php

namespace App\Tests\Utils;

use PHPUnit\Framework\TestCase;

class SomeTest extends TestCase
{
    /**
     * @group legacy
     */
    public function testAdd()
    {
        trigger_error('Pedro', E_USER_DEPRECATED);
        $this->assertEquals(2, 1 + 1);
    }
}