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

class Extension
{
    protected Loader $load;
    protected Config $config;

    function __construct()
    {
        $this->config = Container::get(Config::class);
        $this->load = Container::get(Loader::class);
    }
}
