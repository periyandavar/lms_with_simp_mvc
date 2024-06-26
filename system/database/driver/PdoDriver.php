<?php
/**
 * PdoDriver File Doc Comment
 * php version 7.3.5
 *
 * @category Database
 * @package  Database
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */

namespace System\Database;

use Pdo;
use PdoException;
use System\Core\Log;
use Error;

defined('VALID_REQ') or exit('Invalid request');
/**
 * PdoDriver Class Handles the data base operations with PDO connection
 *
 * @category   Database
 * @package    Database
 * @subpackage PdoDriver
 * @author     Periyandavar <periyandavar@gmail.com>
 * @license    http://license.com license
 * @link       http://url.com
 */

class PdoDriver extends Database
{
    /**
     * Instantiate a new PdoDriver instance
     *
     * @param string $host   Host Name
     * @param string $user   User Name
     * @param string $pass   Password
     * @param string $db     Database Name
     * @param string $driver Driver Name
     */
    private function __construct(
        string $host,
        string $user,
        string $pass,
        string $db,
        string $driver
    ) {
        try {
            $this->con = new PDO("$driver:host=$host;dbname=$db;", $user, $pass);
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_NUM);
        } catch (PDOException $e) {
            Log::getInstance()->fatal(
                "Unable to establish db connection, ". $exception->getMessage(),
            );
            die();
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
     * Return same PdoDriver instance to perform singletone
     *
     * @param string $host   Host Name
     * @param string $user   User Name
     * @param string $pass   Password
     * @param string $db     Database Name
     * @param string $driver Driver Name
     *
     * @return PdoDriver
     */
    public static function getInstance(
        string $host,
        string $user,
        string $pass,
        string $db,
        string $driver
    ) {
        self::$instance = self::$instance
            ?? new static($host, $user, $pass, $db, $driver);
        return self::$instance;
    }

    /**
     * Executes the query
     *
     * @return bool
     */
    public function executeQuery(): bool
    {
        $flag = false;
        try {
            $stmt = $this->con->prepare($this->query);
            $index = 1;
            foreach ((array)$this->bindValues as $bindValue) {
                $paramType = gettype($bindValue) == 'integer'
                    ? PDO::PARAM_INT
                    : PDO::PARAM_STR;
                $stmt->bindValue($index, $bindValue, $paramType);
                $index++;
            }
            $flag = $stmt->execute();
            if ($flag == true) {
                $this->result = $stmt;
            }
        } catch (PDOException $e) {
            Log::getInstance()->error(
                "Exception: ".$e->getMessage(),
                [
                    "sql" => $this->query,
                    "bind values" => $this->bindValues
                ]
            );
        } catch (Error $e) {
            Log::getInstance()->error(
                "Exception: ".$e->getMessage(),
                [
                    "sql" => $this->query,
                    "bind values" => $this->bindValues
                ]
            );
        }
        return $flag;
    }

    /**
     * Fetch the records
     *
     * @return mixed
     */
    public function fetch()
    {
        return $this->result != null ? $this->result->fetch(PDO::FETCH_OBJ) : null;
    }

    /**
     * Directly run the passed query value
     *
     * @param string $sql        Query
     * @param array  $bindValues Values to be bind
     *
     * @return bool
     */
    public function runQuery(string $sql, array $bindValues=[]): bool
    {
        $flag = false;
        try {
            $stmt = $this->con->prepare($sql);
            $index = 1;
            foreach ($bindValues as $bindValue) {
                switch (gettype($bindValue)) {
                case 'integer':
                    $paramType = PDO::PARAM_INT;
                    break;
                default:
                    $paramType = PDO::PARAM_STR;
                    break;
                }
                $stmt->bindValue($index, $value, $paramType);
                $index++;
            }
            $flag = $stmt->execute();
            if ($flag == true) {
                $this->result = $stmt;
            }
        } catch (PDOException $e) {
            Log::getInstance()->error(
                "Exception: ".$e->getMessage(),
                [
                    "sql" => $this->query,
                    "bind values" => $this->bindValues
                ]
            );
        } catch (Error $e) {
            Log::getInstance()->error(
                "Exception: ".$e->getMessage(),
                [
                    "sql" => $this->query,
                    "bind values" => $this->bindValues
                ]
            );
        }
        return $flag;
    }

    /**
     * Close the Database Connection
     *
     * @return void
     */
    public function close()
    {
        $this->con = null;
    }

    /**
     * Returns the last insert Id
     *
     * @return int
     */
    public function insertId(): int
    {
        return $this->con->lastInsertId();
    }

    /**
     * Begin the transaction
     *
     * @return bool
     */
    public function begin(): bool
    {
        return $this->con->beginTransaction();
    }
}
