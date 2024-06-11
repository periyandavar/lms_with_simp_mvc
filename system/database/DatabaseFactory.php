<?php
/**
 * DatabaseFactory
 * php version 7.3.5
 *
 * @category DatabaseFactory
 * @package  Database
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */

namespace System\Database;

use System\Core\Log;

defined('VALID_REQ') or exit('Invalid request');
/**
 * Creates the instance of the database based on the DbConfig
 *
 * @category DatabaseFactory
 * @package  Database
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */
class DatabaseFactory
{
    private static $_db;

    /**
     * Creates and returns Database instance
     *
     * @return void
     */
    public static function create()
    {
        global $dbConfig;
        if (isset(self::$_db)) {
            return self::$_db;
        }
        try {
            $driver = explode("/", $dbConfig['driver']);
            $driverclass = "System\Database\\" . $driver[0] . 'Driver';
            $file = "system\database\driver\\" . $driver[0] . 'driver.php';
            if (file_exists($file)) {
                include_once $file;
            } else {
                throw new System\Core\FrameworkException("Invalid Driver");
            }
            $driver = isset($driver[1]) ? $driver[1] : '';
            self::$_db = $driverclass::getInstance(
                $dbConfig['host'],
                $dbConfig['user'],
                $dbConfig['password'],
                $dbConfig['database'],
                $driver
            );
            return self::$_db;
        } catch (Exception $exception) {
            Log::getInstance()->error(
                $exception->getMessage() . " in " . $exception->getFile()
                    ." at line " . $exception->getLine()
            );
            Log::getInstance()->debug(
                "Unable to Register the session driver '$file'"
            );
        }
    }
}
