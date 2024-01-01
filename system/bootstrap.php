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

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)) . DS);
define('APP', ROOT . 'app' . DS);
define('SYSTEM', ROOT . 'system' . DS);
define('TPL', ROOT . 'templates' . DS);
define('TIME', time());
define('JsonDB', ROOT . '/database' . DS);

if (!is_dir(JsonDB)) {
    mkdir(JsonDB, 0777, true);
}

define('MIME_TEXT', array(
    // Text
    '' => 'text/html',
    'twig' => 'text/html',
    'htm' => 'text/html',
    'html' => 'text/html',
    'php' => 'text/html',
    'txt' => 'text/plain',
    'json' => 'application/json',
    'xml' => 'application/xml',
    'rss' => 'application/rss+xml',
));
define('MIME_ASSETS', array(
    // Front-end
    'css' => 'text/css',
    'js' => 'application/javascript',

    // Images
    'jpeg' => 'image/jpeg',
    'jpg' => 'image/jpeg',
    'png' => 'image/png',
    'gif' => 'image/gif',
    'bmp' => 'image/bmp',
    'svg' => 'image/svg+xml',

    // Video
    'mp4' => 'video/mp4',
    'avi' => 'video/x-msvideo',
    'mpeg' => 'video/mpeg',
    'mov' => 'video/quicktime',

    // Archives
    'zip' => 'application/zip',
    'rar' => 'application/x-rar-compressed',
    'tar' => 'application/x-tar',
    'gz' => 'application/gzip',
));
define('MIME_TYPE', array_merge(MIME_TEXT, MIME_ASSETS));
define('MIME_RENDER', array_keys(MIME_TEXT));

if (version_compare(PHP_VERSION, '8.0', '<')) {
    // If the version below 8.0, we stops the script and displays an error
    die('<div style="text-align: center; font-size: xx-large">'
        . '<h3 style="color: #dd0000">ERROR: outdated version of PHP</h3>'
        . 'Your needs PHP 8.0 or higher'
        . '</div>');
}

// Global config
require(ROOT . 'configs' . DS . 'init.php');

// Autoload
require(SYSTEM . 'autoload.php');
require(ROOT . 'vendor' . DS . 'autoload.php');

// global function
require(SYSTEM . 'functions.php');

// Register Services
$config = Container::get(Config::class);
$services = $config->get('services');

foreach ($services as $service) {
    Container::get($service)->register();
}
