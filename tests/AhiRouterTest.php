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
   * @dataProvider routerResponseProvider
   */
  public function testRouterResponse($currentPath, $currentMethod, $currentParams, $action, $params)
  {
    $routes = $this->routes;
    $router = new Router();
    $currentPathArray = $router->createPathArray($currentPath);
    $result = $router->search($routes, $currentPathArray, $currentMethod, $currentParams);
    
    $this->assertSame($action, $result['action']);
    $this->assertEquals($params, $result['params']);
  }
  
  public function routerResponseProvider() {
    return [
      ['/', 'GET', [], 'HomeController@index', []],
      ['/users', 'GET', [], 'UserController@index', []],
      ['/users/1', 'GET', [':user_id'], 'UserController@getUser', [':user_id' => 1]],
      ['/users/foo', 'GET', [':user_id'], 'UserController@getUser', [':user_id' => 'foo']],
      ['/users/1', 'POST', [':user_id'], 'UserController@postUser', [':user_id' => 1]],
      ['/users/foo', 'POST', [':user_id'], 'UserController@postUser', [':user_id' => 'foo']],
      ['/users/1/events', 'GET', [':user_id'], 'UserController@getEventsByUser', [':user_id' => 1]],
      ['/users/foo/events', 'GET', [':user_id'], 'UserController@getEventsByUser', [':user_id' => 'foo']],
      ['/users/1/events/1', 'GET', [':user_id', ':event_id'], 'UserController@getEventByUser', [':user_id' => 1, ':event_id' => 1]],
      ['/users/foo/events/bar', 'GET', [':user_id', ':event_id'], 'UserController@getEventByUser', [':user_id' => 'foo', ':event_id' => 'bar']],
      ['/users/1/events/bar', 'GET', [':user_id', ':event_id'], 'UserController@getEventByUser', [':user_id' => 1, ':event_id' => 'bar']],
      ['/users/foo/events/1', 'GET', [':user_id', ':event_id'], 'UserController@getEventByUser', [':user_id' => 'foo', ':event_id' => 1]],
      ['/users/config', 'GET', [], 'UserController@getConfigByUser', []],
    ];
  }
}
