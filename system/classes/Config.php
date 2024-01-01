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

class Config
{
    private $configs;

    public function __construct()
    {
        $configs = [];

        foreach (glob(ROOT . 'configs' . DS . 'autoload' . DS . '?*.php') as $file) {
            $configs = array_merge($configs, [
                basename($file, '.php') => include($file)
            ]);
        }

        $this->configs = $configs;
    }

    public function get($key = null, $default = null)
    {
        $result = $this->configs;

        if (is_null($key)) {
            return $result;
        }

        if (isset($result[$key])) {
            return $result[$key];
        }

        $paths = explode('.', (string) $key);

        foreach ($paths as $path) {
            if (isset($result[$path])) {
                $result = $result[$path];
            } else {
                return $default;
            }
        }

        return $result;
    }
}
