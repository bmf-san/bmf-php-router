<?php

class Router {
    /**
     * Path parameters
     * @var array
     */
    private $params;

    /**
     * Router constructor
     */
    public function __constuct() {
        $this->params = [];
    }

    /**
     * Create array for search path from current path
     *
     * @param  string $currentPath
     * @return array
     */
    public function createPathArray($currentPath): array
    {
        $currentPathLength = strlen($currentPath);

        $result = [];

        for ($i=0; $i < $currentPathLength; $i++) {
            if ($currentPathLength == 1) {
                // ルートの時
                if ($currentPath{$i} == '/') {
                    $result[] = '/';
                }
            } else {
                if ($currentPath{$i} == '/') {
                    $result[] = '';
                    $target = count($result) - 1;
                } else {
                    $result[$target] .= $currentPath{$i};
                }
            }
        }

        return $result;
    }

    /**
     * Search a path and return action and parameters
     *
     * @param  array $routes
     * @param  array $currentPathArray
     * @param  string $currentMethod
     * @param  array  $currentParams
     * @return array
     */
    public function search($routes, $currentPathArray, $currentMethod, $currentParams = []): array
    {
        $i = 0;
        while ($i < count($currentPathArray)) {
            if ($i == 0) {
                $targetRoutes = $routes['/'];
            }

            // Condition for root
            if ($currentPathArray[$i] == '/') {
                $result = $targetRoutes['SLASH_NODE'];
                break;
            }

            foreach ($targetRoutes as $key => $value) {
                if (isset($currentPathArray[$i])) {
                    if (isset($targetRoutes[$currentPathArray[$i]])) {
                        $targetRoutes = $targetRoutes[$currentPathArray[$i]];
                    } else {
                        // Condition for parameters
                        $targetRoutes = $this->matchParams($currentParams, $targetRoutes, $currentPathArray[$i]);
                    }
                }

                // Condition for last loop
                if ($i == count($currentPathArray) - 1) {
                    $result = $targetRoutes['SLASH_NODE'];
                }

                $i++;
            }
        }

        return [
            'action' => $result[$currentMethod],
            'params' => $this->params,
        ];
    }

    public function matchParams($currentParams, $targetRoutes, $currentPathArrayData) {
        for ($i=0; $i < count($currentParams); $i++) {
            if (isset($targetRoutes[$currentParams[$i]])) {
                $this->params[$currentParams[$i]] = $currentPathArrayData;
                return $targetRoutes[$currentParams[$i]];
            }
        }
    }
}




