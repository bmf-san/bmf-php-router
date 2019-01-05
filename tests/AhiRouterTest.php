<?php 

namespace bmfsan\AhiRouterTest;

use PHPUnit\Framework\TestCase;
use bmfsan\AhiRouter\Router;

class AhiRouterTest extends TestCase 
{
  private $routes = [
      '/' => [
          'END_POINT' => [
              'GET' => 'HomeController@index',
          ],
          'users' => [
              'END_POINT' => [
                  'GET' => 'UserController@index',
              ],
              ':user_id' => [
                  'END_POINT' => [
                      'GET' => 'UserController@getUser',
                      'POST' => 'UserController@postUser',
                  ],
                  'events' =>  [
                      'END_POINT' => [
                          'GET' => 'UserController@getEventsByUser',
                      ],
                      ':event_id' => [
                          'END_POINT' => [
                              'GET' => 'UserController@getEventByUser',
                              'POST' => 'UserController@postEventByUser',
                          ],
                      ],
                  ],
              ],
              'config' => [
                  'END_POINT' => [
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
  public function testCreateArrayFromCurrentPath($currentPath, $expectedPathArray)
  {
    $router = new Router();
    
    $arrayFromCurrentPath = $router->createArrayFromCurrentPath($currentPath);
    
    $this->assertEquals($expectedPathArray, $arrayFromCurrentPath);
  }
  
  /**
   * @test
   * @dataProvider searchProvider
   */
  public function testSearch($currentPath, $requestMethod, $targetParams, $expectedAction, $expectedParams)
  {
    $router = new Router();
    
    $arrayFromCurrentPath = $router->createArrayFromCurrentPath($currentPath);
    
    $result = $router->search($this->routes, $arrayFromCurrentPath, $requestMethod, $targetParams);
    
    $this->assertSame($expectedAction, $result['action']);
    $this->assertEquals($expectedParams, $result['params']);
  }
  
  /**
   * DataProvider for testCreateArrayFromCurrentPath
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
