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

    public function get_data($key = null, $per_page = null, $page = null, $order = null)
    {
        /**
         * $key - the store
         */
        if (!$key) return 'There is not key in get_data()';
        $query = $this->QueryBuilder($key);
        if (!$per_page && !$page && !$order) {
            $data = $query->orderBy(['_id' => 'desc'])->getQuery()->fetch();
        } else {
            if ($page != 0) {
                $page = $page - 1;
            }
            if (!$order) $order = 'time';
            if ($order == 'id') $order = '_id';
            if (!is_array($order)) $order = [$order => 'desc'];
            $total = $this->get_data_count($key);
            $page_max = ceil($total / $per_page);
            if ($page >= $page_max) $page = $page_max;
            if ($page < 1) $page = 1;
            $start = ($page - 1) * $per_page;
            $start = $start > 0 ? $start : 1;
            if ($total < $per_page) {
                $data = $query->limit($per_page)->orderBy($order)->getQuery()->fetch();
            } else {
                $data = $query->limit($per_page)->skip($start)->orderBy($order)->getQuery()->fetch();
            }
        }
        return $data;
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
