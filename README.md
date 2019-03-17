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
```php
<?php

require_once("../src/Router.php");

$router = new bmfsan\AhiRouter\Router();

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

$result = $router->search('/posts/1/token', 'GET', [':id', ':token']);

var_dump($result);
// array(2) {
//     'action' =>
//     string(22) "PostController@preview"
//     'params' =>
//     array(2) {
//         ':id' =>
//         string(1) "1"
//         ':token' =>
//         string(5) "token"
//     }
// }
```
See a example/index.php.

## Contributing

We welcome your issue or pull request from everyone. Please check `ISSUE_TEMPLATE.md` and `PULL_REQUEST_TEMPLATE.md` to contribute.

## License

This project is licensed under the terms of the MIT license.

## Author

bmf - A Web Developer in Japan.

- [@bmf-san](https://twitter.com/bmf_san)
- [bmf-tech](http://bmf-tech.com/)
