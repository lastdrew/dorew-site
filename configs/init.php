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

// URL
define('SITE_SCHEME', 'http://');
define('SITE_HOST', '127.0.0.1');
define('SITE_PATH', '');
define('SITE_URL', SITE_SCHEME . SITE_HOST . SITE_PATH);
// Cookie
define('COOKIE_PATH', '/' . SITE_PATH);

// Time zone
date_default_timezone_set('Asia/Ho_Chi_Minh');

ini_set('session.use_trans_sid', '0');
ini_set('arg_separator.output', '&amp;');
mb_internal_encoding('UTF-8');

ini_set('display_errors', 0);
ini_set('display_startup_errors', 1);
error_reporting(-1);

ini_set('session.cookie_httponly', 1);

if (extension_loaded('zlib')) {
    ini_set('zlib.output_compression', 'On');
    ini_set('zlib.output_compression_level', 3);
}
