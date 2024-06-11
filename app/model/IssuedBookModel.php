<?php
/**
 * IssuedBookModel File Doc Comment
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
 * IssuedBookModel Class Handles the IssuedBookController class data base operations
 *
 * @category   Model
 * @package    Model
 * @subpackage IssuedBookModel
 * @author     Periyandavar <periyandavar@gmail.com>
 * @license    http://license.com license
 * @link       http://url.com
 */
class IssuedBookModel extends BaseModel
{
    /**
     * Returns the Maximum Lend Books
     *
     * @return integer|null
     */
    public function getMaxBooksToLend(): ?int
    {
        $this->db->select('maxBookLend')->from('core_config')->execute();
        return ($result = $this->db->fetch()) ? $result->maxBookLend : null;
    }

    /**
     * Returns fine congigs (maximum lend days and fine amount per day)
     *
     * @return object|null
     */
    public function getFineConfigs(): ?object
    {
        $this->db->select('maxLendDays', 'fineAmtPerDay')
            ->from('core_config')
            ->execute();
        ($result = $this->db->fetch()) or $result = null;
        return $result;
    }

    /**
     * Returns maximum book request and maximum book lend
     *
     * @return object|null
     */
    public function getMaxVals(): ?object
    {
        $this->db->select('maxBookRequest', 'maxBookLend')
            ->from('core_config')
            ->execute();
        ($result = $this->db->fetch()) or $result = null;
        return $result;
    }

    /**
     * Returns the user details
     *
     * @param string $userId User Name
     *
     * @return null|object
     */
    public function getUserDetails(string $userId): ?object
    {
        $this->db->select('user.id id', 'userName', 'fullName', 'mobile', 'email')
            ->selectAs(
                "SUM(IF(`status`.`value` LIKE ?, 1, 0)) request",
                "SUM(IF(`status`.`value` = ?, 1, 0)) lent"
            );
        $this->db->appendBindValues([STATUS_REQ, STATUS_ISSUED]);
        $this->db->from('user')
            ->innerJoin('issued_book ib')
            ->on('ib.userid = user.id')
            ->innerJoin('status')
            ->on('status.code = ib.status')
            ->where('returnAt', '=', DEFAULT_DATE_VAL)
            ->where('user.id', '=', $userId)
            ->where('user.deletionToken', '=', DEFAULT_DELETION_TOKEN)
            ->limit(1)
            ->execute();
        $user = $this->db->fetch() or $result = null;
        return $user;
    }

    /**
     * Returns the book details
     *
     * @param int $id ISBN
     *
     * @return object|null
     */
    public function getBookDetails(int $id): ?object
    {
        $this->db->select(
            'id',
            'name',
            'location',
            'publication',
            'price',
            'stack',
            'coverPic',
            'available',
            'isbn'
        )->from('book')->where('id', '=', $id);
        $flag = $this->db->where('deletionToken', '=', DEFAULT_DELETION_TOKEN)
            ->where('status', '=', 1)
            ->limit(1)
            ->execute();
        $book = $this->db->fetch() or $book = null;
        return $book;
    }


    /**
     * Adds the details of the Issued book
     *
     * @param array $book IssuedBook details
     *
     * @return boolean
     */
    public function addIssuedBook(array $book): bool
    {
        $flag = $flag1 = $flag2 = false;
        $this->db->select('code')
            ->from('status')
            ->where('value', '=', STATUS_ISSUED)
            ->limit(1)
            ->execute();
        if (!$result = $this->db->fetch()) {
            return false;
        }
        $book['status'] = $result->code;
        $this->db->set("autocommit", 0);
        $this->db->begin();
        $flag1 = $this->db->insert('issued_book', $book, ['issuedAt' => 'NOW()'])
            ->execute();
        $this->db->update('book')
            ->setTo('available = available - 1')
            ->where('id', '=', $book['bookId']);
        $flag2 = $this->db->where('deletionToken', '=', DEFAULT_DELETION_TOKEN)
            ->execute();
        (($flag1 && $flag2))
            ? ($flag = $this->db->commit())
            : ($this->db->rollback());
        $this->db->set("autocommit", 1);
        return $flag;
    }

    /**
     * Add the details of the requested book
     *
     * @param string $userId User Id
     * @param string $bookId ISBN Number
     *
     * @return boolean
     */
    public function requestBook(string $userId, string $bookId): bool
    {
        $fields = ['userId' => $userId, 'bookId' => $bookId];
        $flag = $this->db->insert('issued_book', $fields)
            ->execute();
        return $flag;
    }

    /**
     * Returns the issued book details
     *
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
    public function getIssuedBooks(
        int $start = 0,
        int $limit = 10,
        string $sortby = "returnAt",
        string $sortDir = 'DESC',
        ?string $searchKey = null,
        ?string &$tcount = null,
        ?string &$tfcount = null
    ): array {
        $issuedBooks = [];
        $this->db->select(
            'book.isbn',
            'name bookName',
            'user.userName',
            'ib.status',
            'ib.id',
            'status.value status',
            'fine'
        )->selectAs(
            "date_format(issuedAt, '%d-%m-%Y %h:%i:%s') issuedAt",
            "formatReturn(returnAt) returnedAt"
        );
        $this->db->selectAs('DATEDIFF(NOW(), issuedAt) days');
        $this->db->from('issued_book ib')
            ->innerJoin('status')
            ->on('status.code = ib.status')
            ->innerJoin('book')
            ->on('book.id = ib.bookId')
            ->innerJoin('user')
            ->on('user.id = ib.userId')
            ->where('ib.issuedAt', '!=', DEFAULT_DATE_VAL);
        if ($searchKey != null) {
            $this->db->where(
                " (user.username LIKE ? OR "
                ." name LIKE ? OR "
                ." status.value LIKE ? OR "
                ." book.isbn LIKE ? )"
            );
            $this->db->appendBindValues(
                ["%$searchKey%", "%$searchKey%", "%$searchKey%", "%$searchKey%"]
            );
        }
        $this->db->orderBy($sortby, $sortDir)
            ->limit($limit, $start)
            ->execute();
        while ($row = $this->db->fetch()) {
            $issuedBooks[] = $row;
        }
        $this->db->selectAs(
            "COUNT(*) count",
        )->from('issued_book ib')
            ->innerJoin('status')
            ->on('status.code = ib.status')
            ->innerJoin('book')
            ->on('book.id = ib.bookId')
            ->innerJoin('user')
            ->on('user.id = ib.userId')
            ->where('ib.issuedAt', '!=', DEFAULT_DATE_VAL)
            ->execute();
        $tcount = ($result = $this->db->fetch()) ? $result->count : 0;
        if ($searchKey != null) {
            $this->db->selectAs(
                "COUNT(*) count",
            )->from('issued_book ib')
                ->innerJoin('status')
                ->on('status.code = ib.status')
                ->innerJoin('book')
                ->on('book.id = ib.bookId')
                ->innerJoin('user')
                ->on('user.id = ib.userId')
                ->where('ib.issuedAt', '!=', DEFAULT_DATE_VAL);
            $this->db->where(
                " (user.username LIKE ? OR "
                ." name LIKE ? OR "
                ." status.value LIKE ? OR "
                ." book.isbn LIKE ?) "
            );
            $this->db->appendBindValues(
                ["%$searchKey%", "%$searchKey%", "%$searchKey%", "%$searchKey%"]
            );
            $this->db->execute();
            $tfcount = ($result = $this->db->fetch()) ? $result->count : 0;
        } else {
            $tfcount = $tcount;
        }
        return $issuedBooks;
    }

    /**
     * Returns the book requests
     *
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
    public function getRequestBooks(
        int $start = 0,
        int $limit = 10,
        string $sortby = "requestedAt",
        string $sortDir = 'DESC',
        ?string $searchKey = null,
        ?string &$tcount = null,
        ?string &$tfcount = null
    ): array {
        $issuedBooks = [];
        $this->db->select(
            'book.isbn',
            'name bookName',
            'user.userName',
            'comments',
            'ib.status',
            'ib.id',
            'status.value status'
        )->selectAs(
            "date_format(requestedAt, '%d-%m-%Y %h:%i:%s') requestedAt",
        );
        $this->db->from('issued_book ib')
            ->innerJoin('status')
            ->on('status.code = ib.status')
            ->innerJoin('book')
            ->on('book.id = ib.bookId')
            ->innerJoin('user')
            ->on('user.id = ib.userId')
            ->where('user.deletionToken', '=', DEFAULT_DELETION_TOKEN)
            ->where('book.deletionToken', '=', DEFAULT_DELETION_TOKEN);
        $this->db->where('status.value', 'LIKE', STATUS_REQ);
        if ($searchKey != null) {
            $this->db->where(
                " (user.username LIKE ? OR "
                ." comments LIKE ? OR "
                ." status.value LIKE ? OR "
                ." name LIKE ? OR "
                ." book.isbn LIKE ? )"
            );
            $this->db->appendBindValues(
                [
                    "%$searchKey%",
                    "%$searchKey%",
                    "%$searchKey%",
                    "%$searchKey%",
                    "%$searchKey%"
                ]
            );
        }
        $this->db->orderBy($sortby, $sortDir)
            ->limit($limit, $start)
            ->execute();
        while ($row = $this->db->fetch()) {
            $issuedBooks[] = $row;
        }
        $this->db->selectAs(
            "COUNT(*) count",
        )->from('issued_book ib')
            ->innerJoin('status')
            ->on('status.code = ib.status')
            ->innerJoin('book')
            ->on('book.id = ib.bookId')
            ->innerJoin('user')
            ->on('user.id = ib.userId')
            ->where('status.value', 'LIKE', STATUS_REQ)
            ->execute();
        $tcount = ($result = $this->db->fetch()) ? $result->count : null;
        if ($searchKey != null) {
            $this->db->selectAs(
                "COUNT(*) count",
            )->from('issued_book ib')
                ->innerJoin('status')
                ->on('status.code = ib.status')
                ->innerJoin('book')
                ->on('book.id = ib.bookId')
                ->innerJoin('user')
                ->on('user.id = ib.userId')
                ->where('status.value', 'LIKE', STATUS_REQ);
            $this->db->where(
                " user.username LIKE ? OR "
                ." comments LIKE ? OR "
                ." status.value LIKE ? OR "
                ." name LIKE ? OR "
                ." book.isbn LIKE ? "
            );
            $this->db->appendBindValues(
                [
                    "%$searchKey%",
                    "%$searchKey%",
                    "%$searchKey%",
                    "%$searchKey%",
                    "%$searchKey%"
                ]
            );
            $this->db->execute();
            $tfcount = ($result = $this->db->fetch()) ? $result->count : null;
        } else {
            $tfcount = $tcount;
        }
        return $issuedBooks;
    }

    /**
     * Returns the book request details
     *
     * @param int $id Request Id
     *
     * @return null|object
     */
    public function getRequestDetails(int $id): ?object
    {
        $this->db->select(
            'userId',
            'userName',
            'fullName',
            'mobile',
            'email',
            'bookId',
            'name',
            'location',
            'publication',
            'price',
            'stack',
            'coverPic',
            'available',
            'isbn',
            'comments'
        )
            ->from('issued_book ib')
            ->innerJoin('user')
            ->on('user.id = ib.userId')
            ->innerJoin('book')
            ->on('book.id = ib.bookId')
            ->innerJoin('status')
            ->on('status.code = ib.status')
            ->where('ib.id', '=', $id)
            ->where('status.value', 'LIKE', STATUS_REQ)
            ->where('user.deletionToken', '=', DEFAULT_DELETION_TOKEN)
            ->where('book.deletionToken', '=', DEFAULT_DELETION_TOKEN)
            ->limit(1);
        $this->db->execute();
        ($result = $this->db->fetch()) or $result = null;
        return $result;
    }

    /**
     * Returns lent books count
     *
     * @param int $userId User Id
     *
     * @return int|null
     */
    public function lentBooksCount(int $userId): ?int
    {
        $this->db->selectAs(
            "SUM(IF(`status`.`value` = ?, 1, 0)) lent"
        );
        $this->db->appendBindValues([STATUS_ISSUED]);
        $this->db->from('issued_book')
            ->innerJoin('status')
            ->on('status.code = status')
            ->where('userId', '=', $userId)
            ->execute();
        return ($result = $this->db->fetch()) ? $result->lent : null;
    }

    /**
     * Mark the Issued book as Returned
     *
     * @param integer $id IssuedBook Id
     *
     * @return boolean
     */
    public function bookReturned(int $id): bool
    {
        $finSettings = $this->getFineConfigs();
        $this->db->select('code')
            ->from('status')
            ->where('value', '=', STATUS_RETURNED)
            ->execute();
        if (!$result = $this->db->fetch()) {
            return false;
        }
        $book['status'] = $result->code;
        $data = [
            'returnAt = NOW()',
            "fine = IF(? < DATEDIFF(now(), issuedAt), "
                . "((DATEDIFF(now(), issuedAt) - ?) * "
                . "? ) ,0)"
        ];
        $this->db->update('issued_book', $book)
            ->setTo(...$data)
            ->appendBindValues(
                [
                    $finSettings->maxLendDays,
                    $finSettings->maxLendDays,
                    $finSettings->fineAmtPerDay
                ]
            )
            ->where('id', '=', $id);
        $this->db->execute();
        $this->db->select('bookId')->from('issued_book')->where('id', '=', $id);
        $flag = $this->db->execute();
        if ($flag && $row = $this->db->fetch()) {
            $data = "available = available +1";
            $this->db->update('book')->setTo($data)->where('id', '=', $row->bookId);
            $this->db->execute();
        }
        return $flag;
    }

    /**
     * Update the Book request Details
     *
     * @param int    $id       Request Id
     * @param int    $status   Status Id
     * @param string $comments Comment
     *
     * @return boolean
     */
    public function updateRequest(int $id, int $status, string $comments): bool
    {
        if ($status != 2) {
            $status = ($status == 1) ? STATUS_REQ_ACCEPTED : STATUS_REQ_REJECTED;
            $this->db->select('code')
                ->from('status')
                ->where('value', '=', $status)
                ->execute();
            if (!$result = $this->db->fetch()) {
                return false;
            }
            $status = $result->code;
            $values = [
                'status' => $status,
                'comments' => $comments
            ];
            $this->db->update('issued_book', $values)->where('id', '=', $id);
            return $this->db->execute();
        } else {
            $this->db->select('code')
                ->from('status')
                ->where('value', '=', STATUS_ISSUED)
                ->execute();
            if (!$result = $this->db->fetch()) {
                return false;
            }
            $status = $result->code;
            $this->db->select('bookId')
                ->from('issued_book')
                ->where('id', '=', $id)
                ->execute();
            if (!$result = $this->db->fetch()) {
                return false;
            }
            $bookId = $result->bookId;
            $values = [
                'status' => $status,
                'comments' => $comments
            ];
            $this->db->set("autocommit", 0);
            $this->db->begin();
            $flag1 = $this->db->update('issued_book', $values)
                ->setTo('issuedAt = NOW()')
                ->where('id', '=', $id)
                ->execute();
            $this->db->update('book')
                ->setTo('available = available - 1')
                ->where('id', '=', $bookId);
            $flag2 = $this->db->where('deletionToken', '=', DEFAULT_DELETION_TOKEN)
                ->execute();
            (($flag1 && $flag2))
                ? ($flag = $this->db->commit())
                : ($this->db->rollback());
            $this->db->set("autocommit", 1);
            return $flag;
        }
    }
}
