<?php
/**
 * GenderModel
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
 * GenderModel
 *
 * @category   Model
 * @package    Model
 * @subpackage GenderModel
 * @author     Periyandavar <periyandavar@gmail.com>
 * @license    http://license.com license
 * @link       http://url.com
 */
class GenderModel extends BaseModel
{
    /**
     * Returns available gender codes
     *
     * @return array
     */
    public function getCodes(): array
    {
        $genders = [];
        $this->db->select('code')->from('gender');
        $this->db->where('deletionToken', '=', DEFAULT_DELETION_TOKEN)->execute();
        while ($row = $this->db->fetch()) {
            $genders[] = $row->code;
        }
        return $genders;
    }

    /**
     * Returns avaialbe gender values with code
     *
     * @return array
     */
    public function get(): array
    {
        $genders = [];
        $i = 0;
        $this->db->select('code', 'value')->from('gender');
        $this->db->where('deletionToken', '=', DEFAULT_DELETION_TOKEN)->execute();
        while ($row = $this->db->fetch()) {
            $genders[$i]['code'] = $row->code;
            $genders[$i]['value'] = $row->value;
            $i++;
        }
        return $genders;
    }
}
