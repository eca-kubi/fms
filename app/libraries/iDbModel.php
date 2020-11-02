<?php


interface iDbModel
{
    public function getSingle();

    public function getMultiple();

    public function insertNew(array $new_record);

    public function insert();

    public function insertNewMulti(array $new_multi_records);

    public function insertMulti();

    public function delete($id);

    public function update();

    public function has(string $column, $value);
}
