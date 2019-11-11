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
