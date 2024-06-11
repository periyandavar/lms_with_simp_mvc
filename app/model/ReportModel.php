<?php
/**
 * ReportModel File Doc Comment
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
 * ReportModel Class Handles the ReportController class data base operations
 *
 * @category   Model
 * @package    Model
 * @subpackage ReportModel
 * @author     Periyandavar <periyandavar@gmail.com>
 * @license    http://license.com license
 * @link       http://url.com
 */
class ReportModel extends BaseModel
{
    /**
     * Returns Top Books List
     *
     * @param string      $sDate     start date
     * @param string      $eDate     end date
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
    public function getTopBookList(
        string $sDate,
        string $eDate,
        int $start = 0,
        int $limit = 10,
        string $sortby = "rank",
        string $sortDir = 'DESC',
        ?string $searchKey = null,
        ?string &$tcount = null,
        ?string &$tfcount = null
    ): array {
        $records = [];
        $tcount = $tfcount = 0;
        $this->db->selectAs('count(*) as count')
            ->from('issued_book')
            ->where("requestedAt BETWEEN ? AND ?");
        $this->db->appendBindValues([$sDate, $eDate]);
        $this->db->execute();
        $tot = ($result = $this->db->fetch()) ? $result->count : 0;
        $this->db->select(
            'b.name',
            'b.id',
            'isbn',
            'authors',
            'categories',
        )->selectAs(
            'count(ib.bookId) rank'
        )->from('book_detail b')
            ->leftJoin('issued_book ib')
            ->on('b.id = ib.bookId')
            ->where("requestedAt BETWEEN ? AND ?");
        $this->db->appendBindValues([$sDate, $eDate]);
        if ($searchKey != null) {
            $this->db->where(
                "(b.name LIKE ?"
                ." OR authors LIKE ? OR "
                ."isbn LIKE ? OR "
                ."categories LIKE ?)"
            );
            $this->db->appendBindValues(
                ["%$searchKey%", "%$searchKey%","%$searchKey%","%$searchKey%"]
            );
        }
        $this->db->groupBy('b.id');
        $this->db->orderBy($sortby, $sortDir)
            ->limit($limit, $start)
            ->execute();
        while ($row = $this->db->fetch()) {
            $row->impression = round(($row->rank / $tot) * 100, 2);
            $records[] = $row;
        }
        $this->db->selectAs(
            "COUNT(*) count",
        )->from('book_detail b')
            ->leftJoin('issued_book ib')
            ->on('b.id = ib.bookId')
            ->where("requestedAt BETWEEN ? AND ?");
        $this->db->appendBindValues([$sDate, $eDate]);
        $this->db->groupBy('b.id')
            ->execute();
        while ($row = $this->db->fetch()) {
            $tcount++;
        }
        if ($searchKey != null) {
            $this->db->selectAs(
                "COUNT(*) count",
            )->from('book_detail b')
                ->leftJoin('issued_book ib')
                ->on('b.id = ib.bookId')
                ->where("requestedAt BETWEEN ? AND ?");
            $this->db->appendBindValues([$sDate, $eDate]);
            $this->db->where(
                "(b.name LIKE ? "
                ." OR authors LIKE ? OR "
                ."isbn LIKE ? OR "
                ."categories LIKE ?)"
            );
            $this->db->appendBindValues(
                ["%$searchKey%", "%$searchKey%","%$searchKey%","%$searchKey%"]
            );
            $this->db->groupBy('b.id')
                ->execute();
            while ($row = $this->db->fetch()) {
                $tfcount++;
            }
        } else {
            $tfcount = $tcount;
        }
        return $records;
    }

    /**
     * Returns Top Authors List
     *
     * @param string      $sDate     start date
     * @param string      $eDate     end date
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
    public function getTopAuthorList(
        string $sDate,
        string $eDate,
        int $start = 0,
        int $limit = 10,
        string $sortby = "rank",
        string $sortDir = 'DESC',
        ?string $searchKey = null,
        ?string &$tcount = null,
        ?string &$tfcount = null
    ): array {
        $records = [];
        $this->db->selectAs('count(*) as count')
            ->from('issued_book')
            ->where("requestedAt BETWEEN ? AND ? ");
        $this->db->appendBindValues([$sDate, $eDate]);
        $this->db->execute();
        $tot = ($result = $this->db->fetch()) ? $result->count : 0;
        $this->db->select(
            'a.name',
            'a.id'
        )->selectAs(
            'count(ib.bookId) rank'
        )->from('issued_book ib')
            ->innerJoin('book b')
            ->on('b.id = ib.bookId')
            ->innerJoin('book_author ba')
            ->on('ba.bookId = b.id')
            ->rightJoin('author a')
            ->on('a.id = ba.authorId')
            ->where("requestedAt BETWEEN ? AND ?");
        $this->db->appendBindValues([$sDate, $eDate]);
        if ($searchKey != null) {
            $this->db->where(
                "(b.name LIKE ?"
                ." OR authors LIKE ? OR "
                ."isbn LIKE ? OR "
                ."categories LIKE ?)"
            );
            $this->db->appendBindValues(
                ["%$searchKey%", "%$searchKey%","%$searchKey%","%$searchKey%"]
            );
        }
        $this->db->groupBy('a.id');
        $this->db->orderBy($sortby, $sortDir)
            ->limit($limit, $start)
            ->execute();
        while ($row = $this->db->fetch()) {
            $row->impression = round(($row->rank / $tot) * 100, 2);
            $records[] = $row;
        }
        $this->db->selectAs(
            "COUNT(*) count",
        )->from('issued_book ib')
            ->innerJoin('book b')
            ->on('b.id = ib.bookId')
            ->innerJoin('book_author ba')
            ->on('ba.bookId = b.id')
            ->rightJoin('author a')
            ->on('a.id = ba.authorId')
            ->where("requestedAt BETWEEN ? AND ?");
        $this->db->appendBindValues([$sDate, $eDate]);
        $this->db->groupBy('a.id')
            ->execute();
        while ($row = $this->db->fetch()) {
            $tcount++;
        }
        if ($searchKey != null) {
            $this->db->selectAs(
                "COUNT(*) count",
            )->from('issued_book ib')
                ->innerJoin('book b')
                ->on('b.id = ib.bookId')
                ->innerJoin('book_author ba')
                ->on('ba.bookId = b.id')
                ->rightJoin('author a')
                ->on('a.id = ba.authorId')
                ->where("requestedAt BETWEEN ? AND ?");
            $this->db->appendBindValues([$sDate, $eDate]);
            $this->db->where(
                "(b.name LIKE ?"
                ." OR authors LIKE ? OR "
                ."isbn LIKE ? OR "
                ."categories LIKE ?)"
            );
            $this->db->appendBindValues(
                ["%$searchKey%", "%$searchKey%","%$searchKey%","%$searchKey%"]
            );
            $this->db->groupBy('b.id')
                ->execute();
            while ($row = $this->db->fetch()) {
                $tfcount++;
            }
        } else {
            $tfcount = $tcount;
        }
        return $records;
    }

    /**
     * Returns Top Categories List
     *
     * @param string      $sDate     start date
     * @param string      $eDate     end date
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
    public function getTopCategoryList(
        string $sDate,
        string $eDate,
        int $start = 0,
        int $limit = 10,
        string $sortby = "rank",
        string $sortDir = 'DESC',
        ?string $searchKey = null,
        ?string &$tcount = null,
        ?string &$tfcount = null
    ): array {
        $records = [];
        $this->db->selectAs('count(*) as count')
            ->from('issued_book')
            ->where("requestedAt BETWEEN ? AND ?");
        $this->db->appendBindValues([$sDate, $eDate]);
        $this->db->execute();
        $tot = ($result = $this->db->fetch()) ? $result->count : 0;
        $this->db->select(
            'c.name',
            'c.id',
        )->selectAs(
            'count(ib.id) rank'
        )->from('issued_book ib')
            ->innerJoin('book b')
            ->on('b.id = ib.bookId')
            ->innerJoin('book_category bc')
            ->on('bc.bookId = b.id')
            ->rightJoin('category c')
            ->on('c.id = bc.catId')
            ->where("requestedAt BETWEEN ? AND ?");
        $this->db->appendBindValues([$sDate, $eDate]);
        if ($searchKey != null) {
            $this->db->where(
                "(b.name LIKE ?"
                ." OR authors LIKE ? OR "
                ."isbn LIKE ? OR "
                ."categories LIKE ? )"
            );
            $this->db->appendBindValues(
                ["%$searchKey%", "%$searchKey%","%$searchKey%","%$searchKey%"]
            );
        }
        $this->db->groupBy('c.id');
        $this->db->orderBy($sortby, $sortDir)
            ->limit($limit, $start)
            ->execute();
        while ($row = $this->db->fetch()) {
            $row->impression = round(($row->rank / $tot) * 100, 2);
            $records[] = $row;
        }
        $this->db->selectAs(
            "COUNT(*) count",
        )->from('issued_book ib')
            ->innerJoin('book b')
            ->on('b.id = ib.bookId')
            ->innerJoin('book_category bc')
            ->on('bc.bookId = b.id')
            ->rightJoin('category c')
            ->on('c.id = bc.catId')
            ->where("requestedAt BETWEEN ? AND ?");
        $this->db->appendBindValues([$sDate, $eDate]);
        $this->db->groupBy('c.id')
            ->execute();
        while ($row = $this->db->fetch()) {
            $tcount++;
        }
        if ($searchKey != null) {
            $this->db->selectAs(
                "COUNT(*) count",
            )->from('issued_book ib')
                ->innerJoin('book b')
                ->on('b.id = ib.bookId')
                ->innerJoin('book_category bc')
                ->on('bc.bookId = b.id')
                ->rightJoin('category c')
                ->on('c.id = bc.catId')
                ->where("requestedAt BETWEEN ? AND ?");
            $this->db->appendBindValues([$sDate, $eDate]);
            $this->db->where(
                "(b.name LIKE ?"
                ." OR authors LIKE ? OR "
                ."isbn LIKE ? OR "
                ."categories LIKE ?)"
            );
            $this->db->appendBindValues(
                ["%$searchKey%", "%$searchKey%","%$searchKey%","%$searchKey%"]
            );
            $this->db->groupBy('c.id')
                ->execute();
            while ($row = $this->db->fetch()) {
                $tfcount++;
            }
        } else {
            $tfcount = $tcount;
        }
        return $records;
    }

    /**
     * Returns Top Users List
     *
     * @param string      $sDate     start date
     * @param string      $eDate     end date
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
    public function getTopUserList(
        string $sDate,
        string $eDate,
        int $start = 0,
        int $limit = 10,
        string $sortby = "rank",
        string $sortDir = 'DESC',
        ?string $searchKey = null,
        ?string &$tcount = null,
        ?string &$tfcount = null
    ): array {
        $records = [];
        $this->db->selectAs('count(*) as count')
            ->from('issued_book')
            ->where("requestedAt BETWEEN ? AND ?");
        $this->db->appendBindValues([$sDate, $eDate]);
        $this->db->execute();
        $tot = ($result = $this->db->fetch()) ? $result->count : 0;
        $this->db->select(
            'u.username name',
            'u.id',
            'u.fullName'
        )->selectAs(
            'count(ib.userId) rank'
        )->from('user u')
            ->leftJoin('issued_book ib')
            ->on('u.id = ib.userId')
            ->where("requestedAt BETWEEN ? AND ?");
        $this->db->appendBindValues([$sDate, $eDate]);
        if ($searchKey != null) {
            $this->db->where(
                "(u.username LIKE ?"
                ." OR u.fullname LIKE ?)"
            );
            $this->db->appendBindValues(
                ["%$searchKey%", "%$searchKey%"]
            );
        }
        $this->db->groupBy('u.id');
        $this->db->orderBy($sortby, $sortDir)
            ->limit($limit, $start)
            ->execute();
        while ($row = $this->db->fetch()) {
            $row->impression = round(($row->rank / $tot) * 100, 2);
            $records[] = $row;
        }
        $this->db->selectAs(
            "COUNT(*) count",
        )->from('user u')
            ->leftJoin('issued_book ib')
            ->on('u.id = ib.userId')
            ->where("requestedAt BETWEEN ? AND ?");
        $this->db->appendBindValues([$sDate, $eDate]);
        $this->db->groupBy('u.id')
            ->execute();
        while ($row = $this->db->fetch()) {
            $tcount++;
        }
        if ($searchKey != null) {
            $this->db->selectAs(
                "COUNT(*) count",
            )->from('user u')
                ->leftJoin('issued_book ib')
                ->on('u.id = ib.userId')
                ->where("requestedAt BETWEEN ? AND ?");
            $this->db->appendBindValues([$sDate, $eDate]);
            $this->db->where(
                "(u.username LIKE ?"
                ." OR u.fullname LIKE ?)"
            );
            $this->db->appendBindValues(
                ["%$searchKey%", "%$searchKey%"]
            );
            $this->db->groupBy('u.id')
                ->execute();
            while ($row = $this->db->fetch()) {
                $tfcount++;
            }
        } else {
            $tfcount = $tcount;
        }
        return $records;
    }
}
