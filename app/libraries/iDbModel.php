<?php


interface iDbModel
{
    public static function getEntitySingle(int $id);

    public static function getEntityMultiple();

    public static function insert(array $record);

    public static function insertMulti(array $new_multi_records);

    public static function update(int $id, array $record);

    public function delete(int $id);

    public static function has(string $column, $value);

    public function getInsertId();

}
