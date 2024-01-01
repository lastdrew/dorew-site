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


define('_MVC_START', microtime(true));

require('system/bootstrap.php');

/** @var Kernel */
$kernel = Container::get(Kernel::class);

$kernel->run(Container::get(Request::class));
