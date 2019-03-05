<?php

class Tree
{
    // TODO: あとでアクセサは調整
    public $tree = [];

    public function add($nodeList)
    {
        for ($i=0; $i < count($nodeList); $i++) {
            // treeが空で/しかない時
            if (count($this->tree) == 0) {
                $this->tree['/'] = [];
                $ref = &$this->tree['/'];
            } else {
                // treeが空でないとき
                // $i=0 /のときは何もしない、すでにtreeには/が含まれるので
                if ($nodeList[$i] !== '/') {
                    if ($i == 1) {
                        $ref = &$this->tree['/'];
                    }

                    if (is_array($nodeList[$i])) {
                        // leafの時
                        // TODO: httpメソッド部分対応する
                        $ref['END_POINT'] = $nodeList[$i]['END_POINT'];
                    } else {
                        // すでに同じ名前のノードが存在するかどうか
                        if (isset($ref[$nodeList[$i]])) {
                            if (!is_array($nodeList[$i+1])) {
                                $ref = &$ref[$nodeList[$i]];
                            } else {
                                $ref[$nodeList[$i]][$nodeList[$i+1]] = [];
                                $ref = &$ref[$nodeList[$i]];
                            }
                        } else {
                            // 同じノードが存在しない場合
                            $ref[$nodeList[$i]] = [];
                            $ref = &$ref[$nodeList[$i]];
                        }
                    }
                }
            }
        }
    }

    /**
    * Create a node from path
    * ex. add a END_POINT to leaf
    * / -> [‘/’, ‘END_POINT’]
    * /posts/:id -> [‘/’, ‘posts’, ‘:id’, ['END_POINT' => ['GET' => 'PostController@show']]
    *
    * @param string $nodeKey
    * @param string $nodeMethod
    * @param string $nodeAction
    * @return array
    */
    public function createNodeList($nodeKey, $nodeMethod, $nodeAction)
    {
        $nodeList = [];
        $target = 0;

        if ($nodeKey == '/') {
            $nodeList[] = '/';
        } else {
            for ($i = 0; $i < strlen($nodeKey); $i++) {
                // 先頭の/はroot
                if ($i == 0) {
                    if ($nodeKey{$i} == '/') {
                        $nodeList[] = '/';
                        $nodeList[] = '';
                        $target = 1;
                    }
                } else {
                    if ($nodeKey{$i} == '/') {
                        $nodeList[] = '';
                        ++$target;
                    } else {
                        $nodeList[$target] .= $nodeKey{$i};
                    }
                }
            }
        }

        $nodeList[count($nodeList)] = ['END_POINT' => [
            $nodeMethod => $nodeAction
        ]];

        return $nodeList;
    }
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
