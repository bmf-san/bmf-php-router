<?php 

namespace bmfsan\AhiRouterTest;

use PHPUnit\Framework\TestCase;
use bmfsan\AhiRouter;

class AhiRouterTest extends TestCase {
  public function testExample()
  {
    $stack = [];
        $this->assertSame(0, count($stack));

        array_push($stack, 'foo');
        $this->assertSame('foo', $stack[count($stack)-1]);
        $this->assertSame(1, count($stack));

        $this->assertSame('foo', array_pop($stack));
        $this->assertSame(1, count($stack));
  }
}
