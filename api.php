<?php
/**
 * Entry point of REST request
 * All the rest requests are handled by this file
 * php version 7.3.5
 *
 * @category Index
 * @package  Index
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */
define("VALID_REQ", true);
define("API_REQ", true);
use System\Core\FrameworkException;
use System\Core\Router;
use System\Core\Log;

/**
 * Define error handler
 */
if (!function_exists("errHandler")) {
    /**
     * Error handler
     *
     * @param $errNo   Error level
     * @param $errMsg  Error Message
     * @param $errFile Error File
     * @param $errLine Error Line
     *
     * @return void
     */
    function errHandler($errNo, $errMsg, $errFile, $errLine)
    {
        ob_get_contents() and ob_end_clean();
        Log::getInstance()->error(
            $errMsg . ' in ' . $errFile . ' at line ' . $errLine
        );
        echo json_encode(["error" => 'server error']);
    }
}

/**
 * Define exception handler
 */
if (!function_exists("exceptionHandler")) {
    /**
     * Error handler
     *
     * @param $exception Exception object
     *
     * @return void
     */
    function exceptionHandler($exception)
    {
        ob_get_contents() and ob_end_clean();
        Log::getInstance()->error(
            $exception->getMessage() . " in " . $exception->getFile() ." at line "
                . $exception->getLine()
        );
        echo json_encode(["error" => 'server error']);
    }
}

require_once 'app.php'; // Intialize the application


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,PATCH,DELETE");
header("Access-Control-Max-Age: 3600");
header(
    "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers,"
    . " Authorization, X-Requested-With"
);
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);
ob_start();
Router::runApi();
$output = ob_get_contents();
ob_end_clean();
echo $output;
