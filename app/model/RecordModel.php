<?php
/**
 * RecordModel File Doc Comment
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
 * RecordModel Class
 *
 * @category   Model
 * @package    Model
 * @subpackage RecordModel
 * @author     Periyandavar <periyandavar@gmail.com>
 * @license    http://license.com license
 * @link       http://url.com
 */
class RecordModel extends BaseModel
{
    /**
     * Gets records
     *
     * @param integer|null $id record id
     *
     * @return array
     */
    public function get(?int $id = null): array
    {
        $records = [];
        $this->db->selectAs("name", 'age', 'color')->from('record');
        ($id != null) and $this->db->where('id', '=', $id);
        $this->db->execute();
        while ($row = $this->db->fetch()) {
            $records[] = $row;
        }
        return $records;
    }

    /**
     * Inserts a new record
     *
     * @param array $fields record values
     *
     * @return boolean
     */
    public function insert(array $fields): bool
    {
        return $this->db->insert('record', $fields)->execute();
    }

    /**
     * Updates the records values
     *
     * @param int   $id     record Id
     * @param array $fields record new values
     *
     * @return boolean
     */
    public function update(int $id, array $fields): bool
    {
        return $this->db->update('record', $fields)
            ->where('id', '=', $id)
            ->execute();
    }

    /**
     * Deletes the record
     *
     * @param integer $id record id
     *
     * @return boolean
     */
    public function delete(int $id): bool
    {
        return $this->db->delete('record')->where('id', '=', $id)->execute();
    }
}
