<?php

use Twig\Extension\ExtensionInterface;
use Twig\TwigFunction;

class RequestExtension extends Extension implements ExtensionInterface
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getFunctions()
    {
        return [
            # cookie
            new TwigFunction('get_uri_segments', [$this, 'get_uri_segments']),
            new TwigFunction('request_method', [$this, 'request_method']),
            new TwigFunction('get_post', [$this, 'get_post']),
            new TwigFunction('get_get', [$this, 'get_get']),
            new TwigFunction('get_youtube_id', [$this, 'get_youtube_id']),
            new TwigFunction('get_youtube_title', [$this, 'get_youtube_title']),
            new TwigFunction('ip', [$this, 'ip']),
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

    public function get_uri_segments()
    {
        $uri = preg_replace('#^/#', '', $_SERVER['REQUEST_URI']);
        $uri = explode('?', $uri)[0];
        $uri = explode('/', $uri);
        return $uri;
    }

    public function request_method()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function get_post($string)
    {
        return ($_POST[$string]) ? htmlspecialchars($_POST[$string]) : null;
    }

    public function get_get($string)
    {
        return isset($_GET[$string]) ? htmlspecialchars($_GET[$string]) : null;
    }

    public function get_youtube_id($url)
    {
        preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user|shorts)\/))([^\?&\"'>]+)/", $url, $matches);
        return $matches[1];
    }

    public function get_youtube_title($url)
    {
        $youtube_id = $this->get_youtube_id($url);
        $youtube_title = file_get_contents("https://www.youtube.com/watch?v=$youtube_id");
        preg_match("/<title>(.*)<\/title>/", $youtube_title, $matches);
        return str_replace(' - YouTube', '', $matches[1]);
    }

    public function ip()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        return $ip;
    }
}
