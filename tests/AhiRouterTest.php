<?php 

namespace bmfsan\AhiRouterTest;

use PHPUnit\Framework\TestCase;
use bmfsan\AhiRouter;

class AhiRouterTest extends TestCase 
{
  private $routes = [
      '/' => [
          'SLASH_NODE' => [
              'GET' => 'PATH: / METHOD: GET',
          ],
          'users' => [
              'SLASH_NODE' => [
                  'GET' => 'PATH: /users METHOD: GET',
              ],
              ':user_id' => [
                  'SLASH_NODE' => [
                      'GET' => 'PATH: /users/:user_id METHOD: GET',
                      'POST' => 'PATH: /users/:user_id METHOD: POST',
                  ],
                  'events' =>  [
                      'SLASH_NODE' => [
                          'GET' => 'PATH: /users/:user_id/events METHOD: GET',
                      ],
                      ':event_id' => [
                          'SLASH_NODE' => [
                              'GET' => 'PATH: /users/:user_id/events/:event_id METHOD: GET',
                              'POST' => 'PATH: /users/:user_id/events/:event_id METHOD: POST',
                          ],
                      ],
                  ],
              ],
              ':event_id' => [
                  'SLASH_NODE' => [
                      'GET' => 'PATH: /users/:event_id METHOD: GET',
                      'POST' => 'PATH: /users/:event_id METHOD: POST',
                  ],
              ],
              'support' => [
                  'SLASH_NODE' => [
                      'GET' => 'PATH: /users/support METHOD: GET',
                  ],
              ],
          ],
      ],
  ];

  public function testExample()
  {
    $stack = [];
        $this->assertSame(0, count($stack));

        array_push($stack, 'foo');
        $this->assertSame('foo', $stack[count($stack)-1]);
        $this->assertSame(1, count($stack));

        $this->assertSame('foo', array_pop($stack));
        $this->assertSame(0, count($stack));
  }
}
