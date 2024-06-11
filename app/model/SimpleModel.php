<?php
/**
 * SimpleModel File Doc Comment
 * php version 7.3.5
 *
 * @category Model
 * @package  Model
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */

namespace App\Model;

defined('VALID_REQ') or exit('Invalid request');
use System\Core\BaseModel;

/**
 * SimpleModel Class
 *
 * @category   Model
 * @package    Model
 * @subpackage SimpleModel
 * @author     Periyandavar <periyandavar@gmail.com>
 * @license    http://license.com license
 * @link       http://url.com
 */
class SimpleModel extends BaseModel
{
    /**
     * Register a new user
     *
     * @param array $fields user record
     * 
     * @return bool
     */
    public function register($fields): bool
    {
        $fields['password'] = md5($fields['password']);
        $flag = $this->db->insert('user', $fields)->execute();
        return  $flag;
    }

    /**
     * Returns the password of the given username
     *
     * @param string $username User Name
     *
     * @return object|null
     */
    public function getUser(string $username): ?object
    {
        $this->db->select('password', 'id', 'email');
        $this->db->from('user');
        $this->db->where('username', '=', $username);
        $this->db->where('deletionToken', '=', DEFAULT_DELETION_TOKEN);
        $this->db->execute();
        $user = $this->db->fetch() or $user = null;
        return $user;
    }
}