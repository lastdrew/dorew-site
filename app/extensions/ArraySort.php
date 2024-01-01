<?php

use Twig\Extension\ExtensionInterface;
use Twig\TwigFunction;
use Twig\TwigFilter;

class ArraySortExtension extends Extension implements ExtensionInterface
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getFunctions()
    {
        return [
            new TwigFunction('shuffle', [$this, 'shuffle_array']),
            new TwigFunction('shuffle_array', [$this, 'shuffle_array']),
            new TwigFunction('shuffle_string', [$this, 'shuffle_string']),
        ];
    }
    public function getFilters()
    {
        return [
            new TwigFilter('array_unique', [$this, 'twig_array_unique']),

            new TwigFilter('get_keys', [$this, 'twig_get_keys']),
            new TwigFilter('rsort', [$this, 'twig_rsort']),
            new TwigFilter('asort', [$this, 'twig_asort']),
            new TwigFilter('ksort', [$this, 'twig_ksort']),
            new TwigFilter('arsort', [$this, 'twig_arsort']),
            new TwigFilter('krsort', [$this, 'twig_krsort']),

            new TwigFilter('shuffle', [$this, 'shuffle_array']),
            new TwigFilter('shuffle_array', [$this, 'shuffle_array']),
            new TwigFilter('shuffle_string', [$this, 'shuffle_string']),

            new TwigFilter('sum', [$this, 'sum']),
            new TwigFilter('product', [$this, 'product']),
            new TwigFilter('values', [$this, 'values']),
            new TwigFilter('as_array', [$this, 'asArray']),
            new TwigFilter('html_attr', [$this, 'htmlAttributes']),
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

    public function twig_array_unique($array)
    {
        return array_unique($array);
    }

    /* --- SORT --- */
    /**
     * sort and reverse are twig's two default filters
     * some other filters: get_keys, rsort, asort, ksort, arsort, krsort, shuffle
     */


    public function twig_get_keys($array)
    {
        return array_keys($array);
    }

    public function twig_rsort($array)
    {
        rsort($array);
        return $array;
    }

    public function twig_asort($array)
    {
        asort($array);
        return $array;
    }

    public function twig_ksort($array)
    {
        ksort($array);
        return $array;
    }

    public function twig_arsort($array)
    {
        arsort($array);
        return $array;
    }

    public function twig_krsort($array)
    {
        krsort($array);
        return $array;
    }

    public function shuffle_string($string)
    {
        $array = str_split($string);
        shuffle($array);
        return implode('', $array);
    }
    public function push($array, $value)
    {
        $array[] = $value;
        return $array;
    }
    public function shuffle_array($array)
    {
        shuffle($array);
        return $array;
    }

    /**
     * Calculate the sum of values in an array
     *
     * @param array $array
     * @return int
     */
    public function sum($array)
    {
        return isset($array) ? array_sum((array)$array) : null;
    }


    /**
     * Calculate the product of values in an array
     *
     * @param array $array
     * @return int
     */
    public function product($array)
    {
        return isset($array) ? array_product((array)$array) : null;
    }


    /**
     * Return all the values of an array or object
     *
     * @param array|object $array
     * @return array
     */
    public function values($array)
    {
        return isset($array) ? array_values((array)$array) : null;
    }


    /**
     * Cast value to an array
     *
     * @param object|mixed $value
     * @return array
     */
    public function asArray($value)
    {
        return is_object($value) ? get_object_vars($value) : (array)$value;
    }


    /**
     * Cast an array to an HTML attribute string
     *
     * @param mixed $array
     * @return string
     */
    public function htmlAttributes($array)
    {
        if (!isset($array)) {
            return null;
        }
        $str = "";
        foreach ($array as $key => $value) {
            if (!isset($value) || $value === false) {
                continue;
            }
            if ($value === true) {
                $value = $key;
            }
            $str .= ' ' . $key . '="' . addcslashes($value, '"') . '"';
        }
        return trim($str);
    }
}
