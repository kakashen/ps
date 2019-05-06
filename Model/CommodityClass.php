<?php

namespace Model;

class CommodityClass extends MysqlDB
{
    private $tableName = 'os_commodityclass';
    private $id;
    const ccIndex = 'ccIndex';

    function __construct(string $id = null)
    {
        $this->id = (int)$id;
        parent::__construct($this->tableName);
    }

    /**
     * @return array
     * 根据主键 ccIndex 获取一条数据
     */
    public function findOne(): array
    {
        return $this->find($this->tableName, [self::ccIndex => $this->id]);
    }

    /**
     * @param array $filter
     * @param array $option
     * @param string $select
     * @return array 获取多条数据
     */
    private function getMany(array $filter = [], array $option = [], string $select = "*")
    {
        $query = $this->select($select)->from($this->tableName)->where($filter);

        if (array_key_exists("order", $option)) {
            $query = $query->order($option['order']);
        }

        if (array_key_exists("first", $option)) {
            return $query->queryRow();
        }
        return $query->queryAll();
    }
}