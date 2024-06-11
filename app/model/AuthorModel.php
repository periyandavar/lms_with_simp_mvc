<?php
/**
 * AuthorModel File Doc Comment
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
 * AuthorModel Class Handles the AuthorController class data base operations
 *
 * @category   Model
 * @package    Model
 * @subpackage AuthorModel
 * @author     Periyandavar <periyandavar@gmail.com>
 * @license    http://license.com license
 * @link       http://url.com
 */
class AuthorModel extends BaseModel
{
    /**
     * Returns all available categories
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
    public function getAll(
        int $start = 0,
        int $limit = 10,
        string $sortby = "1",
        string $sortDir = 'ASC',
        ?string $searchKey = null,
        ?string &$tcount = null,
        ?string &$tfcount = null
    ): array {
        $author = [];
        $this->db->select("id", "name", "status")
            ->selectAs(
                "date_format(createdAt, '%d-%m-%Y %h:%i:%s') createdAt",
                "date_format(updatedAt, '%d-%m-%Y %h:%i:%s') updatedAt"
            )
            ->from('author');
        $this->db->where('deletionToken', '=', DEFAULT_DELETION_TOKEN);
        if ($searchKey != null) {
            $this->db->where('name', "LIKE", "%$searchKey%");
        }
        $this->db->orderBy($sortby, $sortDir)
            ->limit($limit, $start)
            ->execute();
        while ($row = $this->db->fetch()) {
            $author[] = $row;
        }
        $this->db->selectAs(
            "COUNT(*) count",
        )->from('author');
        $this->db->where('deletionToken', '=', DEFAULT_DELETION_TOKEN)
            ->execute();
        $tcount = ($result = $this->db->fetch()) ? $result->count : 0;
        if ($searchKey != null) {
            $this->db->selectAs(
                "COUNT(*) count",
            )->from('author');
            $this->db->where('deletionToken', '=', DEFAULT_DELETION_TOKEN);
            $this->db->where('name', "LIKE", "%$searchKey%")
                ->execute();
            $tfcount = ($result = $this->db->fetch()) ? $result->count : 0;
        } else {
            $tfcount = $tcount;
        }
        return $author;
    }
    /**
     * Adds new author
     *
     * @param array $author Author Details
     *
     * @return boolean
     */
    public function add(array $author): bool
    {
        return $this->db->insert('author', $author)->execute();
    }

    /**
     * Returns the details of the new author
     *
     * @param integer $id Author Id
     *
     * @return object|null
     */
    public function get(int $id): ?object
    {
        $this->db->select('id', 'name')->from('author')->where('id', '=', $id);
        $this->db->where(
            'deletionToken',
            '=',
            DEFAULT_DELETION_TOKEN
        )->limit(1)->execute();
        $author = $this->db->fetch() or $author = null;
        return $author;
    }

    /**
     * Delete the author
     *
     * @param integer $id Author Id
     *
     * @return boolean
     */
    public function delete(int $id): bool
    {
        $deletionToken = uniqid();
        $field = [ 'deletionToken' => $deletionToken];
        $this->db->update('author', $field)->where('id', '=', $id);
        return $this->db->execute();
    }

    /**
     * Update the author details
     *
     * @param array $fields Author Details
     * @param int   $id     Author Id
     *
     * @return boolean
     */
    public function update(array $fields, int $id): bool
    {
        $this->db->update('author', $fields)->where('id', '=', $id);
        $this->db->where('deletionToken', '=', DEFAULT_DELETION_TOKEN);
        return $this->db->execute();
    }

    /**
     * Search for the authors with given search keys
     *
     * @param string $searchKey  Search keys to search author
     * @param string $ignoreList Author codes with , seperator
     *                           which will be ignored on search
     *
     * @return array
     */
    public function getAuthorsLike(string $searchKey, string $ignoreList): array
    {
        $authors = [];
        $this->db->select("id code", "name value")
            ->from('author')
            ->where('name', 'LIKE', "%$searchKey%");
        $this->db->where('deletionToken', '=', DEFAULT_DELETION_TOKEN)
            ->where('status', '=', 1);
        $this->db->where("NOT find_in_set(id, ?)");
        $orderClause = "case when name like ? THEN 0 ";
        $orderClause .= "WHEN name like ? THEN 1 ";
        $orderClause .= "WHEN name like ? THEN 2 else 3 end, name";
        $this->db->appendBindValues(
            ["$ignoreList", "$searchKey%", "'% %$searchKey% %'", "%$searchKey"]
        );
        $this->db->orderBy($orderClause)->execute();
        while ($row = $this->db->fetch()) {
            $authors[] = $row;
        }
        return $authors;
    }
}
