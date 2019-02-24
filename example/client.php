<?php

// TODO load Route.php

$routes = [
    '/' => [
        'END_POINT' => [
            'GET' => 'PATH: / METHOD: GET',
        ],
        'users' => [
            'END_POINT' => [
                'GET' => 'PATH: /users METHOD: GET',
            ],
            ':user_id' => [
                'END_POINT' => [
                    'GET' => 'PATH: /users/:user_id METHOD: GET',
                    'POST' => 'PATH: /users/:user_id METHOD: POST',
                ],
                'events' =>  [
                    'END_POINT' => [
                        'GET' => 'PATH: /users/:user_id/events METHOD: GET',
                    ],
                    ':event_id' => [
                        'END_POINT' => [
                            'GET' => 'PATH: /users/:user_id/events/:event_id METHOD: GET',
                            'POST' => 'PATH: /users/:user_id/events/:event_id METHOD: POST',
                        ],
                    ],
                ],
            ],
            ':event_id' => [
                'END_POINT' => [
                    'GET' => 'PATH: /users/:event_id METHOD: GET',
                    'POST' => 'PATH: /users/:event_id METHOD: POST',
                ],
            ],
            'support' => [
                'END_POINT' => [
                    'GET' => 'PATH: /users/support METHOD: GET',
                ],
            ],
        ],
    ],
];

$currentPath = '/users/1/events/10';
$currentMethod = 'GET';
$currentParams = [
    ':user_id',
    ':event_id',
];

$router = new Router();

$currentPathArray = $router->createArrayFromCurrentPath($currentPath);
var_dump($router->search($routes, $currentPathArray, $currentMethod, $currentParams));
