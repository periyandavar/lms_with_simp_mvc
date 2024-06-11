<?php
/**
 * Intialize the application
 * php version 7.3.5
 *
 * @category App
 * @package  App
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */
defined('VALID_REQ') or exit('Invalid request');
use System\Core\EnvParser;
use System\Core\Loader;
use System\Core\Log;
use System\Core\Constants;

require "vendor/autoload.php";

require_once 'system/core/FrameworkException.php';

require_once 'system/core/Loader.php';

require_once 'system/core/EnvParser.php';
/**
 * Location of config directory
 */
$configDir = 'app/config';
/**
 * Lods env files
 */
try {
    (new EnvParser('.env'))->load();
    /**
     * Loads all configs
     */
    if (file_exists('app/config')) {
        foreach (glob("$configDir/*.php") as $filename) {
            include $filename;
        }
    } else {
        throw new FrameworkException("Unable to load config files");
    }
} catch (FrameWorkException $e) {
    error_log($message . "\n", 3, "system/exceptions.log");
    header('HTTP/1.1 500 Internal Server Error');
    die("Server Error");
}

try {
    Loader::intialize(); // Initialize the Loader
} catch (FrameworkException $e) {
    Log::getInstance()->fatal(
        $exception->getMessage() . " in " . $exception->getFile() ." at line "
            . $exception->getLine()
    );
}
Log::getInstance();
set_exception_handler('exceptionHandler');
set_error_handler("errHandler");

global $config;

/**
 * Sets timezone
 */
isset($config['timezone']) and date_default_timezone_set($config['timezone']);

/**
 * Defines ENVIRONMENT
 */
define('ENVIRONMENT', $config['environment'] ?? Constants::ENV_DEVELOPMENT);

if (defined('ENVIRONMENT')) {
    switch (ENVIRONMENT) {
    case Constants::ENV_DEVELOPMENT:
        error_reporting(E_ALL);
        break;
    case Constants::ENV_TESTING:
    case Constants::ENV_PRODUCTION:
        error_reporting(0);
        break;
    default:
        Log::getInstance()->fatal("Invalid enviroment found");
        header('HTTP/1.1 500 Internal Server Error');
        die("Server Error");
    }
}
