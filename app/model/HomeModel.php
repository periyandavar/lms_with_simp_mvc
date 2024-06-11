<?php
/**
 * HomeModel File Doc Comment
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
 * HomeModel Class Handles the HomeController class data base operations
 *
 * @category   Model
 * @package    Model
 * @subpackage HomeModel
 * @author     Periyandavar <periyandavar@gmail.com>
 * @license    http://license.com license
 * @link       http://url.com
 */
class HomeModel extends BaseModel
{
    /**
     * Returns all the available books
     *
     * @return array
     */
    public function getAvailableBooks(): array
    {
        $books = [];
        $this->db->select(
            'id',
            'name',
            'authors',
            'description',
            'available',
            'coverPic'
        )->from('book_detail');
        $this->db->where('status', '=', 1)
            ->orderby('RAND()')
            ->limit(12)
            ->execute();
        while ($row = $this->db->fetch()) {
            $books[] = $row;
        }
        return $books;
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

    /**
     * Inserts a record to pasword reset data
     *
     * @param array $data Data
     *
     * @return bool
     */
    public function addPassRest($data)
    {
        return $this->db->insert('password_reset', $data)->execute();
    }

    /**
     * Creates new user account
     *
     * @param array $fields User details
     *
     * @return bool
     */
    public function createAccount(array $fields): bool
    {
        $fields['password'] = md5($fields['password']);
        $flag = $this->db->insert('user', $fields)->execute();
        return  $flag;
    }

    /**
     * Returns the footer area content
     *
     * @return object|null
     */
    public function getFooterData(): ?object
    {
        $this->db->select(
            'aboutUs',
            'address',
            'mobile',
            'email'
        )->from('cms');
        $this->db->where('id', '=', 1)->limit(1)->execute();
        $footer = $this->db->fetch() or $footer = null;
        return $footer;
    }

    /**
     * Returns the Vision
     *
     * @return string|null
     */
    public function getVision(): ?string
    {
        $this->db->select('vision')
            ->from('cms')
            ->where('id', '=', 1)
            ->limit(1)
            ->execute();
        return ($result = $this->db->fetch()) ? $result->vision : null;
    }

    /**
     * Returns the Mission
     *
     * @return string|null
     */
    public function getMission(): ?string
    {
        $this->db->select('mission')
            ->from('cms')
            ->where('id', '=', 1)
            ->limit(1)
            ->execute();
        return ($result = $this->db->fetch()) ? $result->mission : null;
    }

    /**
     * Validates a token
     *
     * @param string $token Token
     *
     * @return object|null
     */
    public function validateToken(string $token)
    {
        $this->db->select('id', 'userId', 'role', 'expireAt')
            ->from('password_reset')
            ->where('token', '=', $token)
            ->execute();
        if (!($result = $this->db->fetch())) {
            return null;
        }
        $this->db->delete('password_reset')
            ->where('id', '=', $result->id)
            ->execute();
        return $result;
    }
}
