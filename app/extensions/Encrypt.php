<?php

use Twig\Extension\ExtensionInterface;
use Twig\TwigFunction;
use Twig\TwigFilter;

class EncryptExtension extends Extension implements ExtensionInterface
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getFunctions()
    {
        return [
            new TwigFunction('md5', [$this, 'twig_md5']),
            new TwigFunction('sha1', [$this, 'twig_sha1']),
            new TwigFunction('htmlspecialchars', [$this, 'twig_htmlspecialchars']),

            new TwigFunction('truncate', [$this, 'twig_truncate']),

            new TwigFunction('json_decode', [$this, 'twig_json_decode']),
            new TwigFunction('json_encode', [$this, 'twig_json_encode']),
            new TwigFunction('url_decode', [$this, 'twig_url_decode']),
            new TwigFunction('ju_encode', [$this, 'twig_ju_encode']),
            new TwigFunction('ju_decode', [$this, 'twig_ju_decode']),
        ];
    }
    public function getFilters()
    {
        return [
            new TwigFilter('md5', [$this, 'twig_md5']),
            new TwigFilter('sha1', [$this, 'twig_sha1']),
            new TwigFilter('htmlspecialchars', [$this, 'twig_htmlspecialchars']),

            new TwigFilter('truncate', [$this, 'twig_truncate']),

            new TwigFilter('json_decode', [$this, 'twig_json_decode']),
            new TwigFilter('json_encode', [$this, 'twig_json_encode']),
            new TwigFilter('url_decode', [$this, 'twig_url_decode']),
            new TwigFilter('ju_encode', [$this, 'twig_ju_encode']),
            new TwigFilter('ju_decode', [$this, 'twig_ju_decode']),
        ];
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

    /* --- ENCRYPTION --- */

    function twig_md5($text)
    {
        return md5($text);
    }

    function twig_sha1($text)
    {
        return sha1($text);
    }

    function twig_htmlspecialchars($text)
    {
        return htmlspecialchars($text);
    }

    /* --- TRUNCATE --- */

    function twig_truncate($string, int $start = 0, int $length = 0)
    {
        if ($length == 0) {
            $length = $start;
            $start = 0;
        }
        return substr($string, $start, $length);
    }

    /* --- JSON --- */

    function twig_json_decode($string)
    {
        return json_decode($string, true);
    }
    function twig_json_encode($string)
    {
        return json_encode($string);
    }

    /* --- URL --- */

    function twig_url_decode($string)
    {
        return urldecode($string);
    }

    /* --- URL AND JSON --- */

    function twig_ju_encode($string)
    {
        return urlencode(json_encode($string));
    }

    function twig_ju_decode($string)
    {
        return json_decode(urldecode($string));
    }
}
