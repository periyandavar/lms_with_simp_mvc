<?php
/**
 * ErrorHandler
 * php version 7.3.5
 *
 * @category ErrorHandler
 * @package  Library
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */

namespace System\Library;

defined('VALID_REQ') or exit('Invalid request');
/**
 * ErrorHandler interface
 * User defined Error controller should implement this interface
 *
 * @category ErrorHandler
 * @package  Library
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */
interface ErrorHandler
{
    /**
     * This function will call when page not found error occurs
     *
     * @return void
     */
    public function pageNotFound();

    /**
     * This function will call when the method is not found
     *
     * @return void
     */
    public function invalidRequest();

    /**
     * This function will call when an error occurs
     *
     * @param string|null $msg Error msg
     *
     * @return void
     */
    public function serverError(?string $msg);
}
