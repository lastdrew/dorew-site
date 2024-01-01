<?php

use Twig\Extension\ExtensionInterface;
use Twig\TwigFunction;
use SleekDB\SleekDB;
use SleekDB\QueryBuilder;

class UpdateExtension extends Extension implements ExtensionInterface
{
    private SelectExtension $SelectDB;
    public function __construct()
    {
        parent::__construct();
        $this->SelectDB = $this->load->extension('Select');
    }
    public function getFunctions()
    {
        return [
            new TwigFunction('save_data', [$this, 'save_data']),
            new TwigFunction('update_data_by_id', [$this, 'update_data_by_id']),
            new TwigFunction('delete_data_by_id', [$this, 'delete_data_by_id']),
            new TwigFunction('delete_data_by_key', [$this, 'delete_data_by_key']),
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

    public function save_data($key = null, $data = null)
    {
        if (!$key || !$data) {
            return 'There is not key or data in save_data()';
            exit();
        }

        // Kiểm tra xem store có tên $key đã tồn tại chưa
        $store = SleekDB::store($key, JsonDB);

        // Kiểm tra $data có phải array không
        if (!is_array($data)) {
            $dataInsert = ['data' => $data];
        } else {
            $dataInsert = $data;
        }
        if (empty($dataInsert['time'])) {
            $dataInsert = array_merge($dataInsert, ['time' => date('U')]);
        }

        if ($this->SelectDB->get_data_count($key) < 1) {
            $newstore = SleekDB::store($key, JsonDB);
            $newstore->insert($dataInsert);
        } else {
            $store->insert($dataInsert);
        }

        return $data['_id'] ?? 'Data saved successfully';
    }

    public function update_data_by_id($key = null, $id = null, $data = [])
    {
        if (!$key || !$id || !$data) {
            return 'There is not key or id or data in update_data_by_id()';
            exit();
        }
        $this->SelectDB->check_data($key, $id);
        if (!is_array($data)) {
            $dataUpdate = ['data' => $data];
        } else {
            $dataUpdate = $data;
        }
        //return print_r($dataUpdate);
        $store = SleekDB::store($key, JsonDB);
        $store->update(array_merge(['_id' => $id], $dataUpdate));
        return $this->SelectDB->get_data_count($key);
    }

    public function delete_data_by_id($key = null, $id = null)
    {
        if (!$key || !$id) {
            return 'There is not key or id in delete_data_by_id()';
            exit();
        }
        $this->SelectDB->check_data($key, $id);
        $store = SleekDB::store($key, JsonDB);
        $deleted = $store->deleteById($id);

        return $deleted;
    }

    public function delete_data_by_key($key = null)
    {
        if (!$key) {
            return 'There is not key in delete_data_by_key()';
            exit();
        }
        $store = SleekDB::store($key, JsonDB);
        $deleted = $store->deleteStore();
        return $deleted;
    }
}
