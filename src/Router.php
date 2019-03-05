<?php

namespace bmfsan\AhiRouter;

class Router
{
    /**
     * Path parameters
     * @var array
     */
    private $params = [];

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

    /**
     * Create array for search path from current path
     *
     * @param  string $currentPath
     * @return array
     */
    public function createArrayFromCurrentPath($currentPath): array
    {
        $currentPathLength = strlen($currentPath);

        $arrayFromCurrentPath = [];

        for ($i=0; $i < $currentPathLength; $i++) {
            if ($currentPathLength == 1) {
                // ルートの時
                if ($currentPath{$i} == '/') {
                    $arrayFromCurrentPath[] = '/';
                }
            } else {
                if ($currentPath{$i} == '/') {
                    $arrayFromCurrentPath[] = '';
                    $target = count($arrayFromCurrentPath) - 1;
                } else {
                    $arrayFromCurrentPath[$target] .= $currentPath{$i};
                }
            }
        }

        return $arrayFromCurrentPath;
    }

    /**
     * Search a path and return action and parameters
     *
     * @param  array $routes
     * @param  array $arrayFromCurrentPath
     * @param  string $requestMethod
     * @param  array  $targetParams
     * @return array
     */
    public function search($routes, $arrayFromCurrentPath, $requestMethod, $targetParams = []): array
    {
        $i = 0;
        while ($i < count($arrayFromCurrentPath)) {
            if ($i == 0) {
                $targetArrayDimension = $routes['/'];
            }

            // Condition for root
            if ($arrayFromCurrentPath[$i] == '/') {
                $result = $targetArrayDimension['END_POINT'];
                break;
            }

            foreach ($targetArrayDimension as $key => $value) {
                if (isset($arrayFromCurrentPath[$i])) {
                    if (isset($targetArrayDimension[$arrayFromCurrentPath[$i]])) {
                        $targetArrayDimension = $targetArrayDimension[$arrayFromCurrentPath[$i]];
                    } else {
                        // Condition for parameters
                        $targetArrayDimension = $this->createParams($targetParams, $targetArrayDimension, $arrayFromCurrentPath[$i]);
                    }
                }

                // Condition for last loop
                if ($i == count($arrayFromCurrentPath) - 1) {
                    $result = $targetArrayDimension['END_POINT'];
                }

                $i++;
            }
        }

        return [
            'action' => $result[$requestMethod],
            'params' => $this->params,
        ];
    }

    /**
     * Create parameter data
     *
     * @param  array $targetParams
     * @param  array $targetArrayDimension
     * @param  string $targetPath
     * @return array
     */
    private function createParams($targetParams, $targetArrayDimension, $targetPath)
    {
        for ($i=0; $i < count($targetParams); $i++) {
            if (isset($targetArrayDimension[$targetParams[$i]])) {
                $this->params[$targetParams[$i]] = $targetPath;

                return $targetArrayDimension[$targetParams[$i]];
            }
        }
    }
}
