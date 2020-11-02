<?php

class EmailDbModel extends DbModel
{
    protected string $table = 'emails';
    public ?int $email_id;
    public ?string $subject;
    public ?string $content;
    public ?string $recipient_address;
    public ?string $recipient_name;
    public ?bool $sent;
    public ?string $attachment;

    public function __construct(?array $properties)
    {
        parent::__construct($properties);
    }

    public function has($column, $value)
    {
        $db = Database::getDbh();
        $db->where($column, $value);
        try {
            if ($db->has($this->table)) {
                return 'true';
            }
        } catch (Exception $e) {
        }
        return false;
    }

    // Verify existence of column value

    /**
     * Summary of getEmail
     * @param int $email_id
     * @return object|false
     *
     */
    public function getEmail(int $email_id)
    {
        try {
            return (object)
            Database::getDbh()->where('email_id', $email_id)
                ->objectBuilder()
                ->getOne($this->table);
        } catch (Exception $e) {
        }
        return false;
    }

    public function add($insertData): bool
    {
        try {
            return Database::getDbh()->insert($this->table, $insertData);
        } catch (Exception $e) {
        }
        return false;
    }

    public function getSingle() : Email
    {
        return new Email($this->jsonSerialize());
    }

    public function getMultiple() : EmailCollection
    {
        try {
            $array_values = $this->db->get($this->table);
            return EmailCollection::createFromArrayValues($array_values);
        } catch (Exception $e) {
        }
        return EmailCollection::createFromArrayValues([]);
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public function update()
    {
        try {
            return $this->db->update($this->table, (array)$this->getSingle());
        } catch (Exception $e) {
        }
        return false;
    }
}
