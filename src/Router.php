<?php

namespace bmfsan\AhiRouter;

class Router
{
    /**
     * Path parameters
     * @var array
     */
    private $params = [];

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
                $targetRoutes = $routes['/'];
            }

            // Condition for root
            if ($arrayFromCurrentPath[$i] == '/') {
                $result = $targetRoutes['SLASH_NODE'];
                break;
            }

            foreach ($targetRoutes as $key => $value) {
                if (isset($arrayFromCurrentPath[$i])) {
                    if (isset($targetRoutes[$arrayFromCurrentPath[$i]])) {
                        $targetRoutes = $targetRoutes[$arrayFromCurrentPath[$i]];
                    } else {
                        // Condition for parameters
                        $targetRoutes = $this->matchParams($targetParams, $targetRoutes, $arrayFromCurrentPath[$i]);
                    }
                }

                // Condition for last loop
                if ($i == count($arrayFromCurrentPath) - 1) {
                    $result = $targetRoutes['SLASH_NODE'];
                }

                $i++;
            }
        }

        return [
            'action' => $result[$requestMethod],
            'params' => $this->params,
        ];
    }

    public function matchParams($targetParams, $targetRoutes, $targetPath)
    {
        for ($i=0; $i < count($targetParams); $i++) {
            if (isset($targetRoutes[$targetParams[$i]])) {
                $this->params[$targetParams[$i]] = $targetPath;
                return $targetRoutes[$targetParams[$i]];
            }
        }
    }
}
