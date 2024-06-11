<?php
/**
 * Config File all the configurations of the applications are defined here
 * php version 7.3.5
 *
 * @category Config
 * @package  Config
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */
defined('VALID_REQ') or exit('Invalid request');
/**
 * Base URL of the site
 */
$config['base_url'] = "http://lms.com";
/**
 * Path to Views Directory
 */
$config['view'] = "app/view/";
/**
 * Path to Models Directory
 */
$config['model'] = "app/model/";
/**
 * Path to Controllers Directory
 */
$config['controller'] ="app/controller/";
/**
 * Path to Libraries Directory
 */
$config['library'] = "app/library/";
/**
 * Path to helpers Directory
 */
$config['helper'] = "app/helper/";
/**
 * Path to Layout Directory
 */
$config['layout'] = "static/layout/";
/**
 * Path to services
 */
$config['service'] = "app/service/";
/**
 * Path to static folder
 */
$config['static'] = "";
/**
 * Path to upload folders
 */
$config['upload'] = "upload";
/**
 * Set the name of the controller handles errors
 */
$config['error_ctrl'] = "ErrorController";
/**
 * Set Environment value
 */
$config['environment'] = null;
/**
 * Sets log file
 */
$config['logs'] = "app/log";
/**
 * Session driver available options file|database
 */
$config['session_driver'] = 'database';
// $config['session_driver'] = 'file';
/**
 * Session expiration time
 */
$config['session_expiration'] = 7200;
/**
 * Cookie expiration time
 */
$config['cookie_expiration'] = 86400;
/**
 * Session save path
 */
$config['session_save_path'] = 'session';
// $config['session_save_path'] = 'C:\xampp\LMS\session';
/**
 * Sets default timezone
 */
$config['timezone'] = 'Asia/Kolkata';
