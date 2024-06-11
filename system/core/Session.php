<?php
/**
 * Session
 * php version 7.3.5
 *
 * @category Session
 * @package  Core
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */

namespace System\Core;

use System\Core\Log;

defined('VALID_REQ') or exit('Invalid request');
/**
 * Session class set and manage custom session handlers
 *
 * @category Session
 * @package  Core
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */
class Session
{
    private $_driver;

    private static $_instance;

    /**
     * Instantiate the Session instance
     */
    private function __construct()
    {
        global $config, $dbConfig;
        try {
            session_save_path($config['session_save_path']);
            ini_set("session.gc_maxlifetime", $config['session_expiration']);
            $file = 'system/core/session/'
            . $config['session_driver']
            . 'Session.php';
            $class = $config['session_driver'].'session';
            if (file_exists($file)) {
                include_once "$file";
                $class = "System\Core\\" . $class;
                $this->_driver = new $class();
            } else {
                throw new FrameworkException("Invalid Driver");
            }
            if (isset($this->_driver)) {
                session_set_save_handler(
                    [$this->_driver, 'open'],
                    [$this->_driver, 'close'],
                    [$this->_driver, 'read'],
                    [$this->_driver, 'write'],
                    [$this->_driver, 'destroy'],
                    [$this->_driver, 'gc']
                );
                register_shutdown_function('session_write_close');
            }
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

    /**
     * Disabling cloning the object from outside the class
     * 
     * @return void
     */
    private function __clone()
    {
        
    }

    /**
     * Returns the instance
     *
     * @return Session
     */
    public static function getInstance(): Session
    {
        self::$_instance = self::$_instance ?? new Session();
        return self::$_instance;
    }
}
