<?php

namespace bmfsan\AhiRouteTest\Unit;

use PHPUnit\Framework\TestCase;
use bmfsan\AhiRouter\Router;

class RouterTest extends TestCase
{
    /**
     * Route map
     * @var array
     *
     * GET    /                     IndexController@index
     * GET    /posts                PostController@getPosts
     * GET    /posts/:id            PostController@edit
     * POST   /posts/:id            PostController@update
     * GET    /posts/:id/:token     PostController@preview
     * GET    /posts/:category      PostController@getPostsByCategory
     * GET    /profile              ProfileController@getProfile
     */
    private $routeMap = [
        '/' => [
            'END_POINT' => [
                'GET' => 'IndexController@index',
            ],
            'posts' => [
                'END_POINT' => [
                    'GET' => 'PostController@getPosts',
                ],
                ':id' => [
                    'END_POINT' => [
                        'GET' => 'PostController@edit',
                        'POST' => 'PostController@update',
                    ],
                    ':token' =>  [
                        'END_POINT' => [
                            'GET' => 'PostController@preview',
                        ],
                    ],
                ],
                ':category' => [
                    'END_POINT' => [
                        'GET' => 'PostController@getPostsByCategory',
                    ],
                ],
            ],
            'profile' => [
                'END_POINT' => [
                    'GET' => 'ProfileController@getProfile',
                ],
            ],
        ],
    ];

    /**
     * @test
     */
    public function testAdd()
    {
        $router = new Router();

        $router->add('/', [
            'GET' => 'IndexController@index',
        ]);

        $router->add('/posts', [
            'GET' => 'PostController@getPosts',
        ]);

        $router->add('/posts/:id', [
            'GET' => 'PostController@edit',
            'POST' => 'PostController@update',
        ]);

        $router->add('/posts/:id/:token', [
            'GET' => 'PostController@preview',
        ]);

        $router->add('/posts/:category', [
            'GET' => 'PostController@getPostsByCategory',
        ]);

        $router->add('/profile', [
            'GET' => 'ProfileController@getProfile',
        ]);

        $reflection = new \ReflectionClass($router);
        $property = $reflection->getProperty('routeMap');
        $property->setAccessible(true);

        $this->assertEquals($this->routeMap, $property->getValue($router));
    }
}
