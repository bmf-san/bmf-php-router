<?php 
use bmfsan\AhiRouter;

$routes = [
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
// $currentPath = '/';
// $currentPath = '/users';
$currentPath = '/users/1/events/10';
// $currentPath = '/users/1';
// $currentPath = '/users/support';
$currentMethod = 'GET';
$currentParams = [
    ':user_id',
    ':event_id',
];

$router = new AhiRouter();

$currentPathArray = $router->createPathArray($currentPath);
var_dump($router->search($routes, $currentPathArray, $currentMethod, $currentParams));
