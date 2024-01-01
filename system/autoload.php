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

// Autoload class
function autoload($name) {
    if (preg_match('#[^a-z]#i', $name)) {
        return;
    }

    if (preg_match('/^([a-z]+)Controller$/i', $name, $matches)) {
        $file = APP . 'controllers' . DS . $matches[1] . '.php';
    } elseif (preg_match('/^([a-z]+)Extension$/i', $name, $matches)) {
        $file = APP . 'extensions' . DS . $matches[1] . '.php';
    } elseif (preg_match('/^([a-z]+)Service$/i', $name, $matches)) {
        $file = APP . 'services' . DS . $name . '.php';
        if (!file_exists($file)) {
            $file = SYSTEM . 'services' . DS . $name . '.php';
        }
    } else {
        $file = SYSTEM . 'classes' . DS . $name . '.php';
    }

    if (file_exists($file)) {
        require_once($file);
    }
}

spl_autoload_register('autoload');
