<?php

namespace bmfsan\BmfPhpRouter;

class Router
{
    /**
     * Route map
     *
     * @var array
     */
    private $routeMap = [];

    /**
     * Path parameters
     *
     * @var array
     */
    private $params = [];

    /**
     * Add routing to route map
     *
     * @param string $route
     * @param array $handler
     * @return void
     */
    public function add($route, $handler)
    {
        $nodeList = [];
        $target = 0;

        if ($route == '/') {
            $nodeList[] = '/';
        } else {
            for ($i = 0; $i < strlen($route); $i++) {
                if ($i == 0) {
                    if ($route{$i} == '/') {
                        $nodeList[] = '/';
                        $nodeList[] = '';
                        $target = 1;
                    }
                } else {
                    if ($route{$i} == '/') {
                        $nodeList[] = '';
                        ++$target;
                    } else {
                        $nodeList[$target] .= $route{$i};
                    }
                }
            }
        }

        $nodeList[count($nodeList)] = ['END_POINT' => $handler];

        for ($i=0; $i < count($nodeList); $i++) {
            if (count($this->routeMap) == 0) {
                $this->routeMap['/'] = [];
                $ref = &$this->routeMap['/'];
            } else {
                if ($nodeList[$i] !== '/') {
                    if ($i == 1) {
                        $ref = &$this->routeMap['/'];
                    }

                    if (is_array($nodeList[$i])) {
                        $ref['END_POINT'] = $nodeList[$i]['END_POINT'];
                    } else {
                        // whether same name node exists in nodeList
                        if (isset($ref[$nodeList[$i]])) {
                            if (!is_array($nodeList[$i+1])) {
                                $ref = &$ref[$nodeList[$i]];
                            } else {
                                $ref[$nodeList[$i]][$nodeList[$i+1]] = [];
                                $ref = &$ref[$nodeList[$i]];
                            }
                        } else {
                            $ref[$nodeList[$i]] = [];
                            $ref = &$ref[$nodeList[$i]];
                        }
                    }
                }
            }
        }
    }

    /**
     * Search a path and return action and parameters
     *
     * @param  string $requestUri
     * @param  string $requestMethod
     * @param  array  $targetParams
     * @return array
     */
    public function search($requestUri, $requestMethod, $targetParams = []): array
    {
        $currentPathLength = strlen($requestUri);

        $arrayFromCurrentPath = [];

        for ($i=0; $i < $currentPathLength; $i++) {
            if ($currentPathLength == 1) {
                if ($requestUri[$i] == '/') {
                    $arrayFromCurrentPath[] = '/';
                }
            } else {
                if ($requestUri[$i] == '/') {
                    $arrayFromCurrentPath[] = '';
                    $target = count($arrayFromCurrentPath) - 1;
                } else {
                    $arrayFromCurrentPath[$target] .= $requestUri[$i];
                }
            }
        }

        $i = 0;
        while ($i < count($arrayFromCurrentPath)) {
            if ($i == 0) {
                $targetArrayDimension = $this->routeMap['/'];
            }

            if ($arrayFromCurrentPath[$i] == '/') {
                $result = $targetArrayDimension['END_POINT'];
                break;
            }

            foreach ($targetArrayDimension as $key => $value) {
                if (isset($arrayFromCurrentPath[$i])) {
                    if (isset($targetArrayDimension[$arrayFromCurrentPath[$i]])) {
                        $targetArrayDimension = $targetArrayDimension[$arrayFromCurrentPath[$i]];
                    } else {
                        // set params
                        $targetArrayDimension = (function ($targetParams, $targetArrayDimension, $targetPath) {
                            for ($i=0; $i < count($targetParams); $i++) {
                                if (isset($targetArrayDimension[$targetParams[$i]])) {
                                    $this->params[$targetParams[$i]] = $targetPath;
                                    return $targetArrayDimension[$targetParams[$i]];
                                }
                            }
                        })($targetParams, $targetArrayDimension, $arrayFromCurrentPath[$i]);
                    }
                }

                // For last loop
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
}
