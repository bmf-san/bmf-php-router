<?php

class Tree
{

}

$tree = new Tree();

$tree->add($tree->createNodeList('/', 'GET', 'IndexController@getIndex'));
$tree->add($tree->createNodeList('/posts', 'GET', 'PostController@getPosts'));
$tree->add($tree->createNodeList('/posts/:title', 'GET', 'PostController@getPostsByPostTitle'));
$tree->add($tree->createNodeList('/posts/:title/:token', 'GET', 'PostController@getPostByToken'));
$tree->add($tree->createNodeList('/posts/:category', 'GET', 'PostController@getPostsByCategoryName'));
$tree->add($tree->createNodeList('/hoge', 'GET', 'HogeController@getHoge'));


ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);
ini_set('xdebug.var_display_max_depth', -1);

var_dump($tree->tree);
