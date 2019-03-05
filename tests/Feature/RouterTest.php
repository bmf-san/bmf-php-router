<?php

namespace bmfsan\AhiRouterTest\Feature;

use PHPUnit\Framework\TestCase;
use bmfsan\AhiRouter\Router;

class RouterTest extends TestCase
{
    /**
     * Route definition
     * @var array
     *
     * GET    /                     IndexController@index
     * GET    /posts                PostController@getPosts
     * GET    /posts/:id            PostController@edit
     * POST   /posts/:id            PostController@update
     * GET    /posts/:id/:token     PostController@preview
     * GET    /posts/:category      PostController@getPostsByCategory
     * GET    /profile              ProfileController@getProfile
     *
     * @test
     * @dataProvider routingProvider
     */
    public function testRouting($requestUri, $requestMethod, $targetParams, $expectedAction, $expectedParams)
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

        $result = $router->search($requestUri, $requestMethod, $targetParams);

        $this->assertSame($expectedAction, $result['action']);
        $this->assertEquals($expectedParams, $result['params']);
    }

    /**
     * DataProvider for testRouting
     *
     * @return void
     */
    public function routingProvider()
    {
        /**
         * dataset name => [
         *   request uri, request method, target params, expected action, expected params
         * ]
         */
        return [
            'Route:/' => [
                '/', 'GET', [], 'IndexController@index', []
            ],
            'Route:/posts' => [
                '/posts', 'GET', [], 'PostController@getPosts', []
            ],
            'Route:/posts/:id' => [
                '/posts/1', 'GET', [':id'], 'PostController@edit', [':id' => 1]
            ],
            'Route:/posts/:id' => [
                '/posts/1', 'POST', [':id'], 'PostController@update', [':id' => 1]
            ],
            'Route:/posts/:category' => [
                '/posts/category-test', 'GET', [':category'], 'PostController@getPostsByCategory', [':category' => 'category-test']
            ],
            'Route:/profile' => [
                '/profile', 'GET', [], 'ProfileController@getProfile', []
            ]
        ];
    }
}
