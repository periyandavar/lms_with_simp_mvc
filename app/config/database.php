<?php
/**
 * DbConfig File all the configurations of the database are defined here
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
 * Config keys and meanings
 * host - db host
 * user - db username
 * password - db password
 * database - database name
 * driver - database driver name (ex: mysql)
 */
$dbConfig['host'] = getenv('DB_HOST');

$dbConfig['user'] = getenv('DB_USERNAME');

$dbConfig['password'] = getenv('DB_PASSWORD');

$dbConfig ['database'] = getenv('DB_DATABASE');
/**
 * For pdo  "pdo/driverName" ex: pdo/mysql
 * For mysqli 'mysqli'
 */
$dbConfig['driver'] = 'pdo/mysql';
// $dbConfig['driver'] = 'mysqli';
