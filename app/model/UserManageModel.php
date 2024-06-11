<?php
/**
 * UserManageModel File Doc Comment
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
 * UserManageModel Class Handles the UserManageController class data base operations
 *
 * @category   Model
 * @package    Model
 * @subpackage UserManageModel
 * @author     Periyandavar <periyandavar@gmail.com>
 * @license    http://license.com license
 * @link       http://url.com
 */
class UserManageModel extends BaseModel
{
    /**
     * Returns all Users
     *
     * @param string      $email     This user email id will be ignored in the list
     * @param integer     $start     offset
     * @param integer     $limit     limit value
     * @param string      $sortby    sorting column
     * @param string      $sortDir   sorting direction
     * @param string|null $searchKey search key
     * @param string|null $tcount    stores total records count
     * @param string|null $tfcount   stores filtered records  count
     *
     * @return array
     */
    public function getAllUsers(
        string $email = '',
        int $start = 0,
        int $limit = 10,
        string $sortby = "1",
        string $sortDir = 'ASC',
        ?string $searchKey = null,
        ?string &$tcount = null,
        ?string &$tfcount = null
    ): array {
        $users = [];
        $this->db->select(
            'id',
            'fullName',
            'userName',
            'email',
            'role',
            'mobile'
        )->selectAs(
            "date_format(createdAt, '%d-%m-%Y %h:%i:%s') createdAt",
        )->from('all_user');
        $this->db->where('email', '!=', $email);
        if ($searchKey != null) {
            $this->db->where(
                "(fullname LIKE ?"
                ." OR username LIKE ? OR "
                ." mobile LIKE ? OR "
                ."email LIKE ?)"
            );
            $this->db->appendBindValues(
                ["%$searchKey%", "%$searchKey%", "%$searchKey%", "%$searchKey%"]
            );
        }

        $this->db->orderBy($sortby, $sortDir)
            ->limit($limit, $start)
            ->execute();
        while ($row = $this->db->fetch()) {
            $users[] = $row;
        }
        $this->db->selectAs(
            "COUNT(*) count",
        )->from('all_user')->execute();
        $this->db->where('email', '!=', $email);
        $tcount = ($result = $this->db->fetch()) ? $result->count : 0;
        if ($searchKey != null) {
            $this->db->selectAs(
                "COUNT(*) count",
            )->from('all_user');
            $this->db->where('email', '!=', $email);
            $this->db->where(
                "(fullname LIKE ?"
                ." OR username LIKE ? OR "
                ." mobile LIKE ? OR "
                ."email LIKE ?)"
            );
            $this->db->appendBindValues(
                ["%$searchKey%", "%$searchKey%", "%$searchKey%", "%$searchKey%"]
            );
            $this->db->execute();
            $tfcount = ($result = $this->db->fetch()) ? $result->count : 0;
        } else {
            $tfcount = $tcount;
        }
        return $users;
    }

    /**
     * Returns all the registered users
     *
     * @param integer     $start     offset
     * @param integer     $limit     limit value
     * @param string      $sortby    sorting column
     * @param string      $sortDir   sorting direction
     * @param string      $searchKey search key
     * @param string|null $tcount    stores total records count
     * @param string|null $tfcount   stores filtered records  count
     *
     * @return array
     */
    public function getRegUsers(
        int $start = 0,
        int $limit = 10,
        string $sortby = "1",
        string $sortDir = 'ASC',
        ?string $searchKey = null,
        ?string &$tcount = null,
        ?string &$tfcount = null
    ): array {
        $users = [];
        $this->db->select(
            'id',
            'fullName',
            'userName',
            'email',
            'mobile',
        )->selectAs(
            "date_format(createdAt, '%d-%m-%Y %h:%i:%s') createdAt",
        )->from('all_user');
        $this->db->where('role', '=', 'user');
        if ($searchKey != null) {
            $this->db->where(
                "(fullname LIKE ? "
                ." OR username LIKE ? OR "
                ." mobile LIKE ? OR "
                ."email LIKE ?)"
            );
            $this->db->appendBindValues(
                ["%$searchKey%", "%$searchKey%", "%$searchKey%", "%$searchKey%"]
            );
        }
        $this->db->orderBy($sortby, $sortDir)
            ->limit($limit, $start)
            ->execute();
        while ($row = $this->db->fetch()) {
            $users[] = $row;
        }
        $this->db->selectAs(
            "COUNT(*) count",
        )->from('all_user')
            ->where('role', '=', 'user')
            ->execute();
        $tcount = ($result = $this->db->fetch()) ? $result->count : 0;
        if ($searchKey != null) {
            $this->db->selectAs(
                "COUNT(*) count",
            )->from('all_user');
            $this->db->where(
                "(fullname LIKE ?"
                ." OR username LIKE ? OR "
                ." mobile LIKE ? OR "
                ."email LIKE ?)"
            );
            $this->db->appendBindValues(
                ["%$searchKey%", "%$searchKey%", "%$searchKey%", "%$searchKey%"]
            );
            $this->db->where('role', '=', 'user')
                ->execute();
            $tfcount = ($result = $this->db->fetch()) ? $result->count : 0;
        } else {
            $tfcount = $tcount;
        }
        return $users;
    }

    /**
     * Returns the role codes and values
     *
     * @return array
     */
    public function getAllRoles(): array
    {
        $authors = [];
        $this->db->select('code', 'value')->from('role')->execute();
        while ($row = $this->db->fetch()) {
            $authors[] = $row;
        }
        return $authors;
    }

    /**
     * Adds new admin user
     *
     * @param array $user User details
     *
     * @return boolean
     */
    public function addAdminUser(array $user): bool
    {
        $user['password'] = md5($user['password']);
        $flag = $this->db->insert('admin_user', $user)->execute();
        return  $flag;
    }

    /**
     * Deletes the user
     *
     * @param string      $role User Role
     * @param integer     $id   User Id
     * @param string|null $msg  error msg will be stored
     *
     * @return boolean
     */
    public function delete(string $role, int $id, ?string &$msg = null): bool
    {
        $deletionToken = uniqid();
        $field = [ 'deletionToken' => $deletionToken];
        if ($role == strtolower(REG_USER)) {
            $this->db->selectAs('count(*) count')
                ->from('issued_book')
                ->innerJoin('status')
                ->on('status.code = issued_book.status')
                ->where('userId', '=', $id)
                ->where('status.value', '=', STATUS_ISSUED)
                ->execute();
            if (($result = $this->db->fetch()) && $result->count != 0) {
                $msg = "The user need to return some books, In order to delete "
                        . "his/her record please mark those books as returned "
                        . "and makes a delete request";
                return false;
            }
            $table = 'user';
        } else {
            $table = 'admin_user';
        }
        $this->db->update($table, $field)->where('id', '=', $id);
        return $this->db->execute();
    }

    /**
     * Returns all Role codes
     *
     * @return array
     */
    public function getRoleCodes(): array
    {
        $roles = [];
        $this->db->select('code')->from('role');
        $this->db->execute();
        while ($row = $this->db->fetch()) {
            $roles[] = $row->code;
        }
        return $roles;
    }

    /**
     * Returns the users matching given username
     *
     * @param string $userName userName
     *
     * @return array
     */
    public function getUsersLike(string $userName): array
    {
        $users = [];
        $this->db->select("id code", "userName value")
            ->from('user')->where('userName', 'LIKE', "%" . $userName . "%")
            ->where('deletionToken', '=', DEFAULT_DELETION_TOKEN);
        $orderClause = "case when userName like '$userName%' THEN 0 "
            . "WHEN userName like '% %$userName% %' THEN 1 "
            . "WHEN userName like '%$userName' THEN 2 else 3 end, userName";
        $this->db->orderBy($orderClause)->execute();
        while ($row = $this->db->fetch()) {
            $users[] = $row;
        }
        return $users;
    }
}
