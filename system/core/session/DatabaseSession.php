<?php
/**
 * DatabaseSession
 * php version 7.3.5
 *
 * @category SessionHandler
 * @package  Core
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */

namespace System\Core;

defined('VALID_REQ') or exit('Invalid request');
/**
 * DatabaseSession class, custom session handler
 *
 * @category SessionHandler
 * @package  Core
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */
class DatabaseSession implements \SessionHandlerInterface
{
    /**
     * Database connection object
     *
     * @var Database
     */
    private $_db;

    /**
     * Session Table Name
     *
     * @var [type]
     */
    private $_table;

    /**
     * Establish Db connection
     *
     * @return void
     */
    public function connect()
    {
        $this->_db = \System\Database\DatabaseFactory::create();
    }

    /**
     * Session open
     *
     * @param $savePath    Session path
     * @param $sessionName Session name
     *
     * @return bool
     */
    public function open($savePath, $sessionName): bool
    {
        $key = "bRuD5WYw5wd0rdHR9yLlM6wt2vteuiniQBqE70nAuhU=";
        $iv = "1234567891011121";
        $method = "aes-128-cbc";
        $this->_security = new Security($method, $key, 0, $iv);
        $this->connect();
        $this->_table = $savePath;
        return isset($this->_db);
    }

    /**
     * Session close
     *
     * @return bool
     */
    public function close(): bool
    {
        $this->db = null;
        return true;
    }

    /**
     * Reads data from session
     *
     * @param $sessionId Session Id
     *
     * @return string
     */
    public function read($sessionId)
    {
        $this->_db->select("data")
            ->from($this->_table)
            ->where("sessionId", '=', $sessionId);
        $this->_db->execute();
        if ($row = $this->_db->fetch()) {
            return ($data = $this->_security->decrypt($row->data)) ? $data : '';
        } else {
            return '';
        }
    }

    /**
     * Writes data to the session db
     *
     * @param $sessionId Session id
     * @param $data      Session data
     *
     * @return bool
     */
    public function write($sessionId, $data)
    {
        $access = time();
        $data = $this->_security->encrypt($data);
        $this->_db->select('id')
            ->from($this->_table)
            ->where('sessionId', '=', $sessionId)
            ->limit(1);
        $this->_db->execute();
        if ($this->_db->fetch()) {
            $this->_db->update($this->_table, ["access" => $access, "data" => $data])
                ->where('sessionId', '=', $sessionId)->limit(1);
            return $this->_db->execute();
        } else {
            $this->_db->insert(
                $this->_table,
                ["sessionId"=>$sessionId, "access" => $access, "data" => $data]
            );
            return $this->_db->execute();
        }
    }

    /**
     * Destroy sessions
     *
     * @param $sessionId Session Id
     *
     * @return bool
     */
    public function destroy($sessionId)
    {
        $this->_db->delete($this->_table)->where('sessionId', '=', $sessionId);
        return $this->_db->execute();
    }

    /**
     * Session grabage collector
     *
     * @param int $max Maximum lifetime
     *
     * @return int|bool
     */
    public function gc($max)
    {
        $old = time() - $max;
        $this->_db->delete($this->_table)->where('access', '<', $old);
        return $this->_db->execute();
    }
}
