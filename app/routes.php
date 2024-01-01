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

/** @var Router */
$router = Container::get(Router::class);

# trang chủ
$router->add('/', 'HomeController@index','*');
$router->add('/index.php', 'HomeController@index','*');
$router->add('/index.html', 'HomeController@index','*');
# uri
$router->add('/{uri:.*}', 'HomeController@index','*');
# assets
$router->add('/plugin/{plugin:[\w-]+}', 'HomeController@api', 'GET');