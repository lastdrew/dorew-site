<?php

/**
 * Developer: valedrat
 * Email: khanh65me1@gmail.com
 * 
 * Product: DorewSite for Wap4
 * Release date: 2023-12-27
 * Version: 0.2.0-RC1
 * 
 * License: MIT License (http://www.opensource.org/licenses/mit-license)
 */

class RouteService
{
    public function register()
    {
        $this->loadRoutes();
    }

    protected function loadRoutes()
    {
        require_once ROOT . 'app' . DS . 'routes.php';
    }
}
