<?php 

namespace bmfsan\AhiRouterTest;

use PHPUnit\Framework\TestCase;
use bmfsan\AhiRouter\Router;

class AhiRouterTest extends TestCase 
{
  private $routes = [
      '/' => [
          'SLASH_NODE' => [
              'GET' => 'HomeController@index',
          ],
          'users' => [
              'SLASH_NODE' => [
                  'GET' => 'UserController@index',
              ],
              ':user_id' => [
                  'SLASH_NODE' => [
                      'GET' => 'UserController@getUser',
                      'POST' => 'UserController@postUser',
                  ],
                  'events' =>  [
                      'SLASH_NODE' => [
                          'GET' => 'UserController@getEventsByUser',
                      ],
                      ':event_id' => [
                          'SLASH_NODE' => [
                              'GET' => 'UserController@getEventByUser',
                              'POST' => 'UserController@postEventByUser',
                          ],
                      ],
                  ],
              ],
              'config' => [
                  'SLASH_NODE' => [
                      'GET' => 'UserController@getConfigByUser',
                  ],
              ],
          ],
      ],
  ];

  /**
   * @test
   * @dataProvider createPathArrayProvider
   */
  public function testCreatePathArray($currentPath, $expectedPathArray)
  {
    $routes = $this->routes;
    $router = new Router();
    
    $currentPathArray = $router->createPathArray($currentPath);
    
    $this->assertEquals($expectedPathArray, $currentPathArray);
  }
  
  /**
   * @test
   * @dataProvider searchProvider
   */
  public function testSearch($currentPath, $currentMethod, $currentParams, $expectedAction, $expectedParams)
  {
    $routes = $this->routes;
    $router = new Router();
    
    $currentPathArray = $router->createPathArray($currentPath);
    
    $result = $router->search($routes, $currentPathArray, $currentMethod, $currentParams);
    
    $this->assertSame($expectedAction, $result['action']);
    $this->assertEquals($expectedParams, $result['params']);
  }
  
  /**
   * DataProvider for testCreatePathArray
   *
   * @return array
   */
   public function createPathArrayProvider(): array
   {
     return [
       '/' => [
         '/', ['/']
       ],
       '/users' => [
         '/users', ['users']
       ],
       '/user/1' => [
         '/users/1', ['users', '1']
       ],
       '/users/foo' => [
         '/users/foo', ['users', 'foo']
       ],
       '/users/1' => [
         '/users/1', ['users', '1']
       ],
       '/users/1/events' => [
         '/users/1/events', ['users', '1', 'events']
       ],
       '/users/foo/events' => [
         '/users/foo/events', ['users', 'foo', 'events']
       ],
       '/users/1/events/1' => [
         '/users/1/events/1', ['users', '1', 'events', '1']
       ],
       '/users/foo/events/bar' => [
         '/users/foo/events/bar', ['users', 'foo', 'events', 'bar']
       ],
       '/users/1/events/bar' => [
         '/users/1/events/bar', ['users', '1', 'events', 'bar']
       ],
       '/users/foo/events/1' => [
         '/users/foo/events/1', ['users', 'foo', 'events', '1']
       ],
       '/users/config' => [
         '/users/config', ['users', 'config']
       ],
     ];
   }
   
  /**
   * DataProvider for testSearch
   * *
   * @return array
   */
  public function searchProvider(): array
  {
    return [
      '/' => [
        '/', 'GET', [], 'HomeController@index', []
      ],
      '/users' => [
        '/users', 'GET', [], 'UserController@index', []
      ],
      '/user/1' => [
        '/users/1', 'GET', [':user_id'], 'UserController@getUser', [':user_id' => 1]
      ],
      '/users/foo' => [
        '/users/foo', 'GET', [':user_id'], 'UserController@getUser', [':user_id' => 'foo']
      ],
      '/users/1' => [
        '/users/1', 'POST', [':user_id'], 'UserController@postUser', [':user_id' => 1]
      ],
      '/users/1/events' => [
        '/users/1/events', 'GET', [':user_id'], 'UserController@getEventsByUser', [':user_id' => 1]
      ],
      '/users/foo/events' => [
        '/users/foo/events', 'GET', [':user_id'], 'UserController@getEventsByUser', [':user_id' => 'foo']
      ],
      '/users/1/events/1' => [
        '/users/1/events/1', 'GET', [':user_id', ':event_id'], 'UserController@getEventByUser', [':user_id' => 1, ':event_id' => 1]
      ],
      '/users/foo/events/bar' => [
        '/users/foo/events/bar', 'GET', [':user_id', ':event_id'], 'UserController@getEventByUser', [':user_id' => 'foo', ':event_id' => 'bar']
      ],
      '/users/1/events/bar' => [
        '/users/1/events/bar', 'GET', [':user_id', ':event_id'], 'UserController@getEventByUser', [':user_id' => 1, ':event_id' => 'bar']
      ],
      '/users/foo/events/1' => [
        '/users/foo/events/1', 'GET', [':user_id', ':event_id'], 'UserController@getEventByUser', [':user_id' => 'foo', ':event_id' => 1]
      ],
      '/users/config' => [
        '/users/config', 'GET', [], 'UserController@getConfigByUser', []
      ],
    ];
  }
}
