<?php

namespace DbModels\VisitorAccessForm;

use Database;
use DbModel;
use Exception;
use VisitorAccessForm\Entities\VisitorAccessFormEntity;
use VisitorAccessForm\VisitorAccessFormCollection;
use VisitorAccessForm\VisitorAccessFormModelTrait;

class VisitorAccessFormDbModel extends DbModel
{
    //use ValidationTrait;

    protected static string $table = 'visitor_access_form';
    protected static string $primary_key = 'visitor_access_form_id';
    protected static string $entity_class = VisitorAccessFormEntity::class;

    use VisitorAccessFormModelTrait;

    public function __construct(?array $properties = null, ?array $where_col_val = null)
    {
        parent::__construct($properties, $where_col_val);
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public function add(array $new_record)
    {
        // TODO: Implement add() method.
    }

    public static function getEntitySingle(int $id): VisitorAccessFormEntity
    {
        try {
            return new VisitorAccessFormEntity(Database::getDbh()->where(static::$primary_key, $id)->getOne(static::$table));
        } catch (Exception $e) {
        }
        return new VisitorAccessFormEntity([]);
    }

    public static function getEntityMultiple(): VisitorAccessFormCollection
    {
        try {
            $array_values = Database::getDbh()->get(self::$table);
            return VisitorAccessFormCollection::createFromArrayValues($array_values);
        } catch (Exception $e) {
        }
        return VisitorAccessFormCollection::createFromArrayValues([]);
    }

    public static function exists($visitor_access_form_id): bool
    {
        return static::has('visitor_access_form_id', $visitor_access_form_id);
    }

    /**
     * @param int $user_id
     * @param int $visitor_access_form_id
     * @return bool
     */
    public static function isOwner(int $user_id, int $visitor_access_form_id): bool
    {
        try {
            return Database::getDbh()->where('originator_id', $user_id)
                ->where('visitor_access_form_id', $visitor_access_form_id)
                ->has(self::$table);
        } catch (Exception $e) {

        }
        return false;
    }

    public static function getPendingApprovals(): VisitorAccessFormCollection
    {
        try {
            $array_values = Database::getDbh()->where('approval_completed', 0)
                ->orWhere('approval_completed', NULL, 'IS')
                ->get(static::$table);
            return VisitorAccessFormCollection::createFromArrayValues($array_values);
        } catch (Exception $e) {
        }
        return new VisitorAccessFormCollection();
    }

    public static function getPendingApprovalsForDepartment(int $department_id): VisitorAccessFormCollection
    {
        try {
            $array_values = Database::getDbh()->where('approval_completed', 0)
                ->where('department_id', $department_id)
                ->orWhere('approval_completed', NULL, 'IS')
                ->get(static::$table);
            return VisitorAccessFormCollection::createFromArrayValues($array_values);
        } catch (Exception $e) {
        }
        return new VisitorAccessFormCollection();
    }

    public static function getCompletedApprovals(): VisitorAccessFormCollection
    {
        try {
            $array_values = Database::getDbh()->where('approval_completed', 1)->get(static::$table);
            return VisitorAccessFormCollection::createFromArrayValues($array_values);
        } catch (Exception $e) {
        }
        return new VisitorAccessFormCollection();
    }

    public static function getCompletedApprovalsForDepartment(int $department_id): VisitorAccessFormCollection
    {
        try {
            $array_values = Database::getDbh()
                ->where('approval_completed', 1)
                ->where('department_id', $department_id)
                ->get(static::$table);
            return VisitorAccessFormCollection::createFromArrayValues($array_values);
        } catch (Exception $e) {
        }
        return new VisitorAccessFormCollection();
    }

    public static function hasForm($form_id): bool
    {
       return self::has('visitor_access_form_id', $form_id);
    }
}
