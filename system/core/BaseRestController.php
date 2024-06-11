<?php
/**
 * BaseRestController
 * php version 7.3.5
 *
 * @category Controller
 * @package  Core
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */

namespace System\Core;

defined('VALID_REQ') or exit('Invalid request');
if (!defined('API_REQ')) {
    return;
}
use System\Core\Utility;
use System\Core\Log;
use System\Core\SysController;
/**
 * Super class for all rest based controller. All rest basef controllers should
 * extend this controller
 * BaseRestController class consists of basic level functions for various purposes
 *
 * @category Controller
 * @package  Core
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */
class BaseRestController extends SysController
{

    /**
     * Handles GET requests
     *
     * @return void
     */
    public function get()
    {
    }
    /**
     * Handles POST request
     *
     * @return void
     */
    public function create()
    {
    }
    /**
     * Handles PUT request
     *
     * @return void
     */
    public function update()
    {
    }
    /**
     * Handles DELETE request
     *
     * @return void
     */
    public function delete()
    {
    }
    /**
     * Handles PATCH request
     *
     * @return void
     */
    public function patch()
    {
    }

}
