# bmf-php-router


The simple URL router built with PHP.

[Packagist - bmf-san/bmf-php-router](https://packagist.org/packages/bmf-san/bmf-php-router)

# Installaion
`composer require bmf-san/bmf-php-router`

# Usage
```php
<?php

require_once("../src/Router.php");

$router = new bmfsan\BmfPhpRouter\Router();

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
