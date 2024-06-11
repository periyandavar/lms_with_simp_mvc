<?php
/**
 * BaseService
 * php version 7.3.5
 *
 * @category Service
 * @package  Core
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */

namespace System\Core;

use StdClass;

defined('VALID_REQ') or exit('Invalid request');
/**
 * BaseService class, Base class for all services
 *
 * @category   Service
 * @package    Core
 * @subpackage Service
 * @author     Periyandavar <periyandavar@gmail.com>
 * @license    http://license.com license
 * @link       http://url.com
 */
class BaseService
{
    /**
     * Converts the array into object
     *
     * @param array $data data
     *
     * @return object
     */
    public function toObject(array $data): object
    {
        $obj = new StdClass();
        foreach ($data as $key => $value) {
            $obj->$key = $value;
        }
        return $obj;
    }

    /**
     * Converts the array into array of object
     *
     * @param array $data data
     *
     * @return array
     */
    public function toArrayObjects(array $data): array
    {
        $result = [];
        foreach ($data as $record) {
            $obj = new stdClass();
            foreach ($array as $key => $value) {
                $obj->$key = $value;
            }
            $result[] = $obj;
        }
        return $result;
    }
}
