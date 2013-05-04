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
use Psr\Log\LogLevel;

class UserStorage extends StorageBase
{
    private $table;

    private $entity_class;

    public function __construct(\Doctrine\DBAL\Connection $conn, $entity_class, $table)
    {
        $this->setConnection($conn);
        $this->entity_class = $entity_class;
        $this->table = $table;
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

            if (isset($user[$col])) {
                $to_save[$col] = $user[$col];

                if (is_null($to_save[$col])) {
                    $binding[$col] = \PDO::PARAM_NULL;
                } else {
                    $binding[$col] = $bind;
                }
            }
        }

        try {
            $res = $this->getConnection()->update(
                $this->table,
                $to_save,
                array(
                    'user_id'   => $user['user_id'],
                ),
                $binding
            );
        } catch (\Exception $e) {
            $this->log(
                LogLevel::EMERGENCY,
                '{name}:  caught exception saving user {msg}',
                array('msg' => $e->getMessage())
            );

            $this->throwSaveException($e);
        }

        return $res;
    }

    public function create(UserInterface $user)
    {
        $to_save = $binding = array();

        foreach ($this->getFields() as $col => $bind) {
            if ('user_id' === $col) {
                continue;
            }

            if (isset($user[$col])) {
                $to_save[$col] = $user[$col];

                if (is_null($to_save[$col])) {
                    $binding[$col] = \PDO::PARAM_NULL;
                } else {
                    $binding[$col] = $bind;
                }
            }
        }

        try {
            $res = $this->getConnection()->insert($this->table, $to_save, $binding);
        } catch (\Exception $e) {
            $this->log(
                LogLevel::EMERGENCY,
                '{name}:  caught exception creating user {msg}',
                array('msg' => $e->getMessage())
            );

            $this->throwSaveException($e);
        }

        return $res;
    }

    public function delete(UserInterface $user)
    {
        if (empty($user['user_id'])) {
            throw new \InvalidArgumentException('User must have a user_id value to delete');
        }

        try {
            $res = $this->getConnection()->delete($this->table, array(
                'user_id'   => $user['user_id'],
            ), array(
                'user_id'   => 'integer',
            ));
        } catch (\Exception $e) {
            $this->log(
                LogLevel::EMERGENCY,
                '{name}:  caught exception deleting user {msg}',
                array('msg' => $e->getMessage())
            );

            throw new \Phial\Exception\UserDeleteException(
                'Caught exception deleting user: ' . $e->getMessage(),
                $e
            );
        }

        return $res;
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
            case 'user_email':
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
        $limit = abs(intval($limit));

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

        return 'SELECT ' . implode(', ', array_keys($fields)) . ' FROM ' . $this->table;
    }

    private function toObject(array $user)
    {
        return new $this->entity_class($user);
    }

    private function throwSaveException(\Exception $e)
    {
        $err_code = 0;

        $prev = $e->getPrevious();

        if ($prev) {
            $err_code = $prev->getCode();
        }

        switch($err_code) {
            case '23505':
            case 23505:
                throw new \Phial\Exception\EmailExistsException(
                    'That email is already in use.',
                    $e,
                    23505
                );
                break;
            default:
                throw new \Phial\Exception\UserSaveException(
                    'Caught exception creating user: ' . $e->getMessage(),
                    $e
                );
                break;
        }
    }
}
