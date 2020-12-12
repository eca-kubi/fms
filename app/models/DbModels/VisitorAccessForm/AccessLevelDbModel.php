<?php


namespace DbModels\VisitorAccessForm;


use Database;
use DbModel;
use Exception;
use VisitorAccessForm\AccessLevelCollection;
use VisitorAccessForm\Entities\AccessLevel;

class AccessLevelDbModel extends DbModel
{

    protected static string $primary_key = 'access_level_id';
    protected static string $table = 'access_level';
    protected static string $entity_class = AccessLevel::class;

    public function __construct(?array $properties = null, ?array $where_col_val = null)
    {
        parent::__construct($properties, $where_col_val);
    }

    public static function getEntitySingle(int $id)
    {
        try {
            return new AccessLevel(Database::getDbh()->where(static::$primary_key, $id)->getOne(static::$table));
        } catch (Exception $e) {
        }
        return new AccessLevel([]);
    }

    public static function getEntityMultiple()
    {
        try {
            $array_values = Database::getDbh()->get(static::$table);
            return AccessLevelCollection::createFromArrayValues($array_values);
        } catch (Exception $e) {
        }
        return AccessLevelCollection::createFromArrayValues([]);
    }

    public function delete(int $id)
    {
        // TODO: Implement delete() method.
    }

    public static function getWithFormId(int $visitor_access_form_id) : AccessLevelCollection
    {
        try {
            $result = Database::getDbh()->where('visitor_access_form_id', $visitor_access_form_id)->get(static::$table);
            return AccessLevelCollection::createFromArrayValues($result);
        } catch (Exception $e) {
        }
        return new AccessLevelCollection();
    }

    public static function updateMultiple(array $records, array $duplicateColumns = ['bus_hrs', 'twenty_four_seven'])
    {
        $ids = [];
        try {
            foreach ($records as $record) {
                Database::getDbh()->onDuplicate($duplicateColumns, static::$primary_key);
                if (Database::getDbh()->insert(static::$table, $record)) {
                    $ids[] = Database::getDbh()->getInsertId();
                }
            }
        } catch (Exception $e) {
        }
        return $ids;
    }
}
