<?php

namespace App\Http\Traits;


trait RoleHelper
{
    /**
     * 獲取角色的權限菜單
     * @DateTime 2019-06-20
     * @param    string     $type [all=全部 menu=主菜單]
     * @return   [type]           [description]
     */
    public function getMenus(string $type = 'all')
    {
        $api                    = app('Dingo\Api\Routing\Router');
        $apiRoutes              = $api->getRoutes();

        $apiNameKeyedRoutes     = collect([]);
        $versionNameKeyedRoutes = [];

        foreach ($apiRoutes as $version => $routesCollection) {
            $versionRoutes = collect($routesCollection->getRoutes());
            $versionRoutes
                ->filter(function ($route) use ($type) {
                    $menu = $type == 'menu' ? $route->getMenu() : $route->getPermission();
                    return $menu;
                })
                ->map(function ($route, $key) use ($version, &$versionNameKeyedRoutes, $type) {
                    $menu = explode(':', $type == 'menu' ? $route->getMenu() : $route->getPermission());
                    $name = $route->getName();
                    if (!isset($versionNameKeyedRoutes[$menu[0]])) {
                        $value                            = substr($name, 0, strripos($name, "."));
                        $versionNameKeyedRoutes[$menu[0]] = ['label' => $menu[0], 'value' => $value];
                    }

                    $versionNameKeyedRoutes[$menu[0]]['children'][] = [
                        'label'  => $menu[1],
                        'value' => $name,
                    ];
                });
            $apiNameKeyedRoutes = $apiNameKeyedRoutes->merge($versionNameKeyedRoutes);
        }

        if ($type == 'all') {
            $apiNameKeyedRoutes->prepend([
                'label'  => '超級權限',
                'value' => '*',
            ]);
        }
        return $apiNameKeyedRoutes->values()->toArray();
    }
}
