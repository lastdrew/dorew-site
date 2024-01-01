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

class Controller
{
    protected Loader $load;
    protected Request $request;
    protected Config $config;
    protected Template $view;
    

    function __construct()
    {
        $this->load = Container::get(Loader::class);
        $this->request = Container::get(Request::class);
        $this->config = Container::get(Config::class);

        $this->view = Container::get(Template::class);
    }
}
