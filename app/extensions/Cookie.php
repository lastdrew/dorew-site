<?php

use Twig\Extension\ExtensionInterface;
use Twig\TwigFunction;

class CookieExtension extends Extension implements ExtensionInterface
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getFunctions()
    {
        return [
            # cookie
            new TwigFunction('set_cookie', [$this, 'set_cookie']),
            new TwigFunction('get_cookie', [$this, 'get_cookie']),
            new TwigFunction('delete_cookie', [$this, 'delete_cookie']),

            # session
            new TwigFunction('set_session', [$this,'set_session']),
            new TwigFunction('get_session', [$this, 'get_session']),
            new TwigFunction('delete_session', [$this, 'delete_session']),
        ];
    }
    public function getFilters()
    {
        return [];
    }
    public function getTokenParsers()
    {
        return [];
    }
    public function getNodeVisitors()
    {
        return [];
    }
    public function getTests()
    {
        return [];
    }
    public function getOperators()
    {
        return [];
    }

    public function set_cookie($name, $value, $time = null)
    {
        if (empty($time)) {
            $time = 3600 * 24 * 365;
        }
        setcookie($name, $value, TIME + $time, '/');
        return;
    }
    
    public function delete_cookie($name)
    {
        setcookie($name, '', -1, '/');
        unset($_COOKIE[$name]);
        return;
    }
    
    public function get_cookie($name)
    {
        if (!$_COOKIE[$name]) return false;
        return $_COOKIE[$name];
    }

    public function set_session($name, $value)
    {
        $_SESSION[$name] = $value;
        return;
    }

    public function get_session($name)
    {
        return $_SESSION[$name];
    }

    public function delete_session($name)
    {
        unset($_SESSION[$name]);
        return;
    }
}
