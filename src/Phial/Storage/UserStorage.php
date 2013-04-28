<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Storage;

use Phial\Entity\UserInterface;

class UserStorage extends StorageBase
{
    const USER_TABLE = 'users';

    private $entity_class;

    public function __construct(\Doctrine\DBAL\Connection $conn, $entity_class)
    {
        $this->setConnection($conn);
        $this->entity_class = $entity_class;
    }

    public function save(UserInterface $user)
    {
        if (empty($user['user_id'])) {
            throw new \InvalidArgumentException('User must have a user ID to save.');
        }

        $to_save = $binding = array();
        foreach ($this->getFields() as $col => $bind) {
            if ('user_id' === $col) {
                $binding[$col] = $bind;
                continue;
            }

            if (!isset($user[$col])) {
                $to_save[$col] = $user[$col];

                if (is_null($col)) {
                    $binding[$col] = \PDO::PARAM_NULL;
                } else {
                    $binding[$col] = $bind;
                }
            }
        }

        return $this->getConnection()->update(
            static::USER_TABLE,
            $to_save,
            array(
                'user_id'   => $user['user_id'],
            ),
            $bind
        );
    }

    public function create(UserInterface $user)
    {
        $to_save = $binding = array();

        foreach ($this->getField() as $col => $bind) {
            if ('user_id' === $col) {
                continue;
            }

            if (!isset($user[$col])) {
                $to_save[$col] = $user[$col];

                if (is_null($user[$col])) {
                    $binding[$col] = \PDO::PARAM_NULL;
                } else {
                    $binding[$col] = $bind;
                }
            }
        }

        return $this->getConnection()->insert(static::USER_TABLE, $to_save, $binding);
    }

    public function delete(UserInterface $user)
    {
        if (empty($user['user_id'])) {
            throw new \InvalidArgumentException('User must have a user_id value to delete');
        }

        $this->getConnection()->delete(static::USER_TABLE, array(
            'user_id'   => $user['user_id'],
        ), array(
            'user_id'   => 'integer',
        ));
    }

    public function getBy($column, $value, $raw=false)
    {
        switch ($column) {
            case 'ID':
            case 'id':
            case 'user_id':
                $column = 'user_id';
                break;
            case 'email':
                $column = 'user_email';
                break;
            default:
                throw new \InvalidArgumentException(sprintf('Cannot fetch users by "%s"', $column));
                break;
        }

        $fields = $this->getFields();

        $result = $this->getConnection()->fetchAssoc(
            $this->getSelect() . " WHERE {$column} = :value LIMIT 1",
            array('value' => $value),
            array('value' => $fields[$column])
        );

        if (!$result) {
            throw new \Phial\Exception\UserNotFoundException(sprintf('User with %s %s not found', $column, $value));
        }

        if ($raw) {
            return $result;
        }

        return $this->toObject($result);
    }

    public function all($raw=false, $page=1, $limit=100)
    {
        $page = abs(intval($page));
        $limit = abs(intval($page));

        if ($page < 1) {
            throw new \InvalidArgumentException('Page must be >= 1');
        }

        if (!$limit) {
            throw new \InvalidArgumentException('Limit must be greater than 0');
        }

        // decrement the page. We'll use it to find offset, so
        // we need to make sure page 1 is zero offset.
        $page--;
        $offset = $page * $limit;

        $result = $this->getConnection()->fetchAll(
            $this->getSelect()
            . ' ORDER BY user_email'
            . ' LIMIT ' . $limit . ' OFFSET ' . $offset
        );

        if (!$result) {
            throw new \Phial\Exception\NoUsersFoundException(
                sprintf('No users found for page %d with limit %d', $page, $limit)
            );
        }

        if ($raw) {
            return $result;
        }

        $out = array();
        foreach ($result as $row) {
            $out[] = $this->toObject($row);
        }

        return $out;
    }

    private function getFields()
    {
        return array(
            'user_id'       => 'integer',
            'user_email'    => 'string',
            'first_name'    => 'string',
            'last_name'     => 'string',
            'display_name'  => 'string',
            'user_pass'     => 'string',
            'user_role'     => 'string',
        );
    }

    private function getSelect()
    {
        $fields = $this->getFields();

        return 'SELECT ' . implode(', ', array_keys($fields)) . ' FROM ' . static::USER_TABLE;
    }

    private function toObject(array $user)
    {
        return new $this->entity_class($user);
    }
}
