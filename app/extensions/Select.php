<?php

use Twig\Extension\ExtensionInterface;
use Twig\TwigFunction;
use SleekDB\SleekDB;

class SelectExtension extends Extension implements ExtensionInterface
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getFunctions()
    {
        return [
            new TwigFunction('get_data', [$this, 'get_data']),
            new TwigFunction('get_data_count', [$this, 'get_data_count']),
            new TwigFunction('get_data_by_id', [$this, 'get_data_by_id']),
            new TwigFunction('RealEscape', [$this, 'RealEscape']),
            new TwigFunction('store', [$this, 'QueryBuilder']),
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

    public function RealEscape($string)
    {
        $string = str_replace('ㅤ', ' ', $string);
        $string = trim($string);
        $string = htmlspecialchars($string);
        return (strlen($string) > 1) ? $string : null;
    }

    public function QueryBuilder($key = null)
    {
        if (!$key) {
            return false;
        } else {
            if ($this->get_data_count($key) > 0) {
                $store =  new \SleekDB\Store($key, JsonDB);
                return $store->createQueryBuilder();
            }
        }
    }

    public function check_data($key = null, $id = null)
    {
        if (!$key || !$id) return false;
        $store = SleekDB::store($key, JsonDB);
        if (!$store->exists()) return false;
        $find = $store->findOneBy(['_id', '=', $id]);
        if (!$find) return false;
        return true;
    }

    public function get_data($key = null, $per_page = 10, $page = 1, $order = null)
    {
        /**
         * $key - the store
         */
        if ($page != 0) {
            $page = $page - 1;
        }
        if (!$key) return 'There is not key in get_data()';
        if (!$order) $order = 'time';
        $store = SleekDB::store($key, JsonDB);
        $find = $store->findAll();
        $start = $page * $per_page;
        $data_slice = array_slice($find, $start, $per_page);
        return $data_slice;
    }

    public function get_data_count($key = null)
    {
        if (!$key) return 'There is not key in get_data_count()';
        $store = SleekDB::store($key, JsonDB);
        $find = $store->findAll();
        $count = count($find);
        return $count > 0 ? $count : 0;
    }

    public function get_data_by_id($key = null, $id = null)
    {
        if (!$key || !$id) return 'There is not key or id in get_data_by_id()';
        $store = SleekDB::store($key, JsonDB);
        $data = $store->findOneBy(['_id' => $id]);
        return $data;
    }
}
