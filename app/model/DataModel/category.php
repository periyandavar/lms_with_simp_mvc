<?php
/**
 * Category Model Object 
 * php version 7.3.5
 *
 * @category DataModel
 * @package  DataModel
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */

namespace App\DataModel;

defined('VALID_REQ') or exit('Invalid request');
use System\Library\ORM;
/**
 * Category Model class object model
 *
 * @category   Service
 * @package    Service
 * @subpackage Category
 * @author     Periyandavar <periyandavar@gmail.com>
 * @license    http://license.com license
 * @link       http://url.com
 */
class Category extends Orm
{
    public $id;

    public $name;

    public $status;

}