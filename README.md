# ahi-router
[![Latest Stable Version](https://poser.pugx.org/bmf-san/ahi-router/v/stable)](https://packagist.org/packages/bmf-san/ahi-router)
[![Total Downloads](https://poser.pugx.org/bmf-san/ahi-router/downloads)](https://packagist.org/packages/bmf-san/ahi-router)
[![Latest Unstable Version](https://poser.pugx.org/bmf-san/ahi-router/v/unstable)](https://packagist.org/packages/bmf-san/ahi-router)
[![License](https://poser.pugx.org/bmf-san/ahi-router/license)](https://packagist.org/packages/bmf-san/ahi-router)
[![Monthly Downloads](https://poser.pugx.org/bmf-san/ahi-router/d/monthly)](https://packagist.org/packages/bmf-san/ahi-router)
[![Daily Downloads](https://poser.pugx.org/bmf-san/ahi-router/d/daily)](https://packagist.org/packages/bmf-san/ahi-router)
[![composer.lock](https://poser.pugx.org/bmf-san/ahi-router/composerlock)](https://packagist.org/packages/bmf-san/ahi-router)
[![CircleCI](https://circleci.com/gh/bmf-san/ahi-router/tree/master.svg?style=svg)](https://circleci.com/gh/bmf-san/ahi-router/tree/master)

The simple URL router built with PHP.

Ahiru + Router = ahi-router

Ahiru means "duck" in japanese.

[Packagist - bmf-san/ahi-router](https://packagist.org/packages/bmf-san/ahi-router)

# Installaion
`composer require bmf-san/ahi-router`

# Usage
```:php
<?php
require_once("vendor/autoload.php");

use bmfsan\AhiRouter\Router;

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
// array(2) {
//   'action' =>
//   string(50) "PATH: /users/:user_id/events/:event_id METHOD: GET"
//   'params' =>
//   array(2) {
//     ':user_id' =>
//     string(1) "1"
//     ':event_id' =>
//     string(2) "10"
//   }
// }
``` 


## Contributing

We welcome your issue or pull request from everyone. Please check `ISSUE_TEMPLATE.md` and `PULL_REQUEST_TEMPLATE.md` to contribute.

## License

This project is licensed under the terms of the MIT license.

## Author

bmf - A Web Developer in Japan.

- [@bmf-san](https://twitter.com/bmf_san)
- [bmf-tech](http://bmf-tech.com/)
