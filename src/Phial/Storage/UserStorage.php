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
    const CAP_TABLE  = 'caps';
    const USER_CAPS  = 'user_caps';

    private $entity_class;

    public function __construct(\Doctrine\DBAL\Connection $conn, $entity_class)
    {
        $this->setConnection($conn);
        $this->entity_class = $entity_class;
    }

    public function save(UserInterface $user)
    {
        
    }

    public function create(UserInterface $user)
    {
        
    }

    public function delete(UserInterface $user)
    {
        
    }

    public function getBy($column, $value)
    {
        
    }

    public function get($page=1, $limit=100)
    {
        
    }
}
