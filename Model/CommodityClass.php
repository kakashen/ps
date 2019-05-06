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

    public function findOne(): array
    {
        return $this->find($this->tableName, [self::ccIndex => $this->id]);
    }
}