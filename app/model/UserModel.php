<?php
/**
 * UserModel File Doc Comment
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
 * UserModel Class Handles the UserController class data base operations
 *
 * @category   Model
 * @package    Model
 * @subpackage IssuedBookModel
 * @author     Periyandavar <periyandavar@gmail.com>
 * @license    http://license.com license
 * @link       http://url.com
 */
class UserModel extends BaseModel
{
    /**
     * Returns the user details
     *
     * @param int $id UserId
     *
     * @return object
     */
    public function getProfile(int $id): ?object
    {
        $this->db->select(
            'fullName',
            'userName',
            'gender',
            'mobile',
            'email',
            'u.updatedAt'
        )->from('user u');
        $this->db->where('id', '=', $id);
        $this->db->where('u.deletionToken', '=', DEFAULT_DELETION_TOKEN)->execute();
        $user = $this->db->fetch() or $user = null;
        return $user;
    }

    /**
     * Updates the user details
     *
     * @param int   $userId   UserId
     * @param array $userData User details
     *
     * @return boolean
     */
    public function updateProfile(int $userId, array $userData): bool
    {
        $result = $this->db->update('user', $userData)
            ->where('id', '=', $userId)
            ->execute();
        return $result;
    }

    /**
     * Updates the user password
     *
     * @param int    $userId   User Id
     * @param string $password password
     * @param string $role     Role
     *
     * @return boolean
     */
    public function updatePassword(
        int $userId,
        string $password,
        string $role = REG_USER
    ): bool {
        $table = ($role == REG_USER) ? 'user' : 'admin_user';
        $result = $this->db->update($table, ['password' => md5($password)])
            ->where('id', '=', $userId)
            ->execute();
        return $result;
    }

    /**
     * Returns the lent books details
     *
     * @param int         $userId User Id
     * @param null|int    $tcount Total Records count
     * @param int         $offset Offset
     * @param int         $limit  Row count
     * @param string|null $search Search value
     *
     * @return array
     */
    public function getLentBooks(
        int $userId,
        ?int &$tcount = null,
        int $offset = 0,
        int $limit = 5,
        ?string $search = null
    ): array {
        $books = [];
        $this->db->select('isbn', 'name bookName', 'ib.id')
            ->selectAs(
                "date_format(issuedAt, '%d-%m-%Y %h:%i:%s') issuedAt",
                "IF(returnAt='0000-00-00','Not Return Yet', "
                . "date_format(returnAt, '%d-%m-%Y %h:%i:%s')) returnAt",
                "IFNULL(fine,'-') fine"
            );
        $this->db->from('issued_book ib')
            ->innerJoin('book')
            ->on('book.id = ib.bookId')
            ->innerJoin('user')
            ->on('user.id = ib.userId');
        $this->db->where('user.id', '=', $userId)
            ->where('issuedAt', '!=', '0000-00-00');
        if ($search != null) {
            $this->db->where('(name LIKE ? OR isbn LIKE ?)');
            $this->db->appendBindValues(["%$search%", "%$search%"]);
        }
        $this->db->orderby('returnAt', 'DESC')->limit($limit, $offset)
            ->execute();
        while ($row = $this->db->fetch()) {
            $books[] = $row;
        }
        $this->db->selectAs(
            "COUNT(*) tCount",
        );
        $this->db->from('issued_book ib')
            ->innerJoin('book')
            ->on('book.id = ib.bookId')
            ->innerJoin('user')
            ->on('user.id = ib.userId');
        if ($search != null) {
            $this->db->where('(name LIKE ? OR isbn LIKE ?)');
            $this->db->appendBindValues(["%$search%", "%$search%"]);
        }
        $this->db->where('user.id', '=', $userId)
            ->where('issuedAt', '!=', '0000-00-00')
            ->execute();
        $tcount = ($result = $this->db->fetch()) ? $result->tCount : 0;
        return $books;
    }

    /**
     * Returns the requested books details
     *
     * @param int         $userId UserId
     * @param null|int    $tcount Total Records count
     * @param int         $offset Offset
     * @param int         $limit  Row count
     * @param string|null $search Search value
     *
     * @return array
     */
    public function getRequestedBooks(
        int $userId,
        ?int &$tcount = null,
        int $offset = 0,
        int $limit = 5,
        ?string $search = null
    ): array {
        $books = [];
        $this->db->select(
            'isbn',
            'name bookName',
            'ib.id',
            'requestedAt',
            'status.value status',
            'comments'
        );
        $this->db->from('issued_book ib');
        $this->db->innerJoin('status')
            ->on('status.code = ib.status')
            ->innerJoin('book')
            ->on('book.id = ib.bookId')
            ->innerJoin('user')
            ->on('user.id = ib.userId');
        $this->db->where('user.id', '=', $userId)
            ->where('status.value', 'LIKE', STATUS_REQ);
        if ($search != null) {
            $this->db->where(
                '(name LIKE ? OR isbn LIKE ?  OR status.value LIKE ?'
                .' OR comments LIKE ?)'
            );
            $this->db->appendBindValues(
                ["%$search%", "%$search%", "%$search%", "%$search%"]
            );
        }
        $this->db->orderby('returnAt')
            ->limit($limit, $offset)
            ->execute();
        while ($row = $this->db->fetch()) {
            $books[] = $row;
        }
        $this->db->selectAs(
            "COUNT(*) tCount",
        );
        $this->db->from('issued_book ib');
        $this->db->innerJoin('status')
            ->on('status.code = ib.status')
            ->innerJoin('book')
            ->on('book.id = ib.bookId')
            ->innerJoin('user')
            ->on('user.id = ib.userId');
        $this->db->where('user.id', '=', $userId)
            ->where('status.value', 'LIKE', STATUS_REQ);
        if ($search != null) {
            $this->db->where(
                '(name LIKE ? OR isbn LIKE ?  OR status.value LIKE ?'
                .' OR comments LIKE ?)'
            );
            $this->db->appendBindValues(
                ["%$search%", "%$search%", "%$search%", "%$search%"]
            );
        }
        $this->db->orderby('returnAt')
            ->execute();
        $tcount = ($result = $this->db->fetch()) ? $result->tCount : 0;
        return $books;
    }

    /**
     * Check the user is exiting with the given mail id or not
     *
     * @param string $email email id
     *
     * @return boolean
     */
    public function isEmailAvailable(string $email): bool
    {
        $this->db->select("id")
            ->from('user')
            ->where('email', '=', $email)
            ->where('deletionToken', '=', DEFAULT_DELETION_TOKEN)
            ->execute();
        if ($this->db->fetch()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check the user is exiting with the given username or not
     *
     * @param string $userName username
     *
     * @return boolean
     */
    public function isNameAvailable(string $userName): bool
    {
        $this->db->select("id")
            ->from('user')
            ->where('userName', '=', $userName)
            ->where('deletionToken', '=', DEFAULT_DELETION_TOKEN)
            ->execute();
        if ($this->db->fetch()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Removes user request
     *
     * @param int $id     User request id
     * @param int $userId UserName
     *
     * @return bool
     */
    public function removeRequest(int $id, int $userId): bool
    {
        $this->db->select('code')
            ->from('status')
            ->where('value', '=', STATUS_DEL_REQ)
            ->execute();
        if (!($result = $this->db->fetch())) {
            return false;
        }
        $data['ib.status'] = $result->code;
        $data['ib.deletionToken'] = uniqid();
        $this->db->update(
            'issued_book ib',
            $data,
            null,
            'Inner Join user on ib.userId = user.id'
        );
        $result = $this->db->where('ib.id', '=', $id)
            ->where('user.id', '=', $userId)
            ->execute();
        return $result;
    }
}
