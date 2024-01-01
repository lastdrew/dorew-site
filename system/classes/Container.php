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

 class Container
 {
     private static $instances = [];
 
     public static function get($name, $params = [])
     {
         if (class_exists($name, true)) {
             $hash = $name . md5(serialize($params));
 
             if (!isset(self::$instances[$hash])) {
                 $obj = new $name(...$params);
 
                 if (is_callable($obj)) {
                     self::$instances[$hash] = $obj(...$params);
                 } else {
                     self::$instances[$hash] = $obj;
                 }
             }
 
             return self::$instances[$hash];
         }
     }
 }
 
