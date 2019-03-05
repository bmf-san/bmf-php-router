<?php
$routes = [
    '/' => [
        'END_POINT' => [
            'GET' => 'IndexController@getIndex',
        ],
        'posts' => [
            'END_POINT' => [
                'GET' => 'PostController@getPosts',
            ],
            ':title' => [
                'END_POINT' => [
                    'GET' => 'PostController@getPostByPostTitle',
                ],
                ':token' =>  [
                    'END_POINT' => [
                        'GET' => 'PostController@getPostByToken',
                    ],
                ],
            ],
            ':category_name' => [
                'END_POINT' => [
                    'GET' => 'PostController@getPostsByCategoryName',
                ],
            ],
        ],
        'hoge' => [
            'END_POINT' => [
                'GET' => 'HogeController@getHoge',
            ]
        ]
    ],
];;


ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);
ini_set('xdebug.var_display_max_depth', -1);

var_dump($routes);
