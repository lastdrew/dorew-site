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

class Loader
{

    public function view()
    {
        return $this->load(Template::class);
    }

    public function controller($name)
    {
        $className = $name . 'Controller';

        return $this->load($className);
    }

    public function extension($name)
    {
        $className = $name . 'Extension';

        return $this->load($className);
    }

    private function load($className)
    {
        return Container::get($className);
    }
}
