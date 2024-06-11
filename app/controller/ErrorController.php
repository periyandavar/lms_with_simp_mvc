<?php
/**
 * ErrorController File Doc Comment
 * php version 7.3.5
 *
 * @category Controller
 * @package  Controller
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */

namespace App\Controller;

defined('VALID_REQ') or exit('Invalid request');
use System\Core\BaseController;
use System\Library\ErrorHandler;

/**
 * ErrorController Class Handles the errors
 *
 * @category   Controller
 * @package    Controller
 * @subpackage ErrorController
 * @author     Periyandavar <periyandavar@gmail.com>
 * @license    http://license.com license
 * @link       http://url.com
 */

class ErrorController extends BaseController implements ErrorHandler
{
    /**
     * Handles the page not found error
     *
     * @return void
     */
    public function pageNotFound()
    {
        if (!headers_sent()) {
            header('HTTP/1.1 404 Not Found');
        }
        $data['msg'] = "The Page you're looking for isn't here.";
        $data['msg'] .= "This may be missing or temporarily unavailable.";
        $data['msg'] .= "You can click the button below to go back to the homepage.";
        $this->loadLayout("header.html");
        $this->loadView("pageNotFound", $data);
        $this->loadLayout("footer.html");
    }

    /**
     * Handles the invalid request (i.e) method not found
     *
     * @return void
     */
    public function invalidRequest()
    {
        if (!headers_sent()) {
            header('HTTP/1.1 400 Bad Request');
        }
        $data['msg'] = "Your request is invalid or that service is removed.";
        $data['msg'] = "Please try again later...";
        $this->loadLayout("header.html");
        $this->loadView("pageNotFound", $data);
        $this->loadLayout("footer.html");
    }

    /**
     * Handles the internal server error
     *
     * @param string|null $error Error array
     *
     * @return void
     */
    public function serverError(?string $error = '')
    {
        if (!headers_sent()) {
            header('HTTP/1.1 500 Internal Server Error');
        }
        $data['msg'] = "On error occured while proccessing your request..!";
        $data['msg'] .= "Please check later and retry again...";
        $data['data'] = $error;
        $this->loadLayout("header.html");
        $this->loadView("serverError", $data);
        $this->loadLayout("footer.html");
    }
}
