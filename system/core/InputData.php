<?php
/**
 * InputData
 * php version 7.3.5
 *
 * @category InputData
 * @package  Core
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */

namespace System\Core;

defined('VALID_REQ') or exit('Invalid request');

/**
 * InputData Class used to access the GET, POST and SESSION values
 *
 * @category InputData
 * @package  Core
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */
final class InputData
{
    /**
     * Post data values
     *
     * @var array
     */
    private $_postData;

    /**
     * Get data values
     *
     * @var array
     */
    private $_getData;

    /**
     * Session Data values
     *
     * @var array
     */
    private $_sessionData;

    /**
     * File array values
     *
     * @var array
     */
    private $_fileData;

    /**
     * Instantiate new InputData instance
     */
    public function __construct()
    {
        $this->_postData = $_POST;
        $this->_getData = $_GET;
        $this->_sessionData = (session_status() != PHP_SESSION_NONE)
            ? $_SESSION
            : null;
        $this->_fileData = $_FILES;
    }

    /**
     * This function is used to get the values form GET array
     *
     * @param string|null $key        Key name
     * @param string|null $default    Default value
     * @param bool        $escapeHtml To escape HTML charecters or not default:false
     * @param bool        $trimData   To trim the input
     *
     * @return void
     */
    public function get(
        ?string $key = null,
        ?string $default = null,
        bool $escapeHtml = true,
        bool $trimData = true
    ) {
        $data = $this->_checkKey($this->_getData, $key);
        if (is_array($data)) {
            if ($escapeHtml || $trimData) {
                foreach ($data as $key => $value) {
                    $escapeHtml and ($value = htmlspecialchars($value));
                    $trimData and ($value = trim($value));
                    $data[$key] = $value;
                }
            }
            return $data;
        }
        $data = (($data != null) ? $data : $default);
        ($data != null) and $escapeHtml and $data = htmlspecialchars($data);
        ($data != null) and $trimData and $data = trim($data);
        return $data;
    }

    /**
     * This function is used to get the values form POST array
     *
     * @param string|null $key        Key name
     * @param string|null $default    Default value
     * @param bool        $escapeHtml To escape HTML charecters or not default:false
     * @param bool        $trimData   To trim the input
     *
     * @return void
     */
    public function post(
        ?string $key = null,
        ?string $default = null,
        bool $escapeHtml = true,
        bool $trimData = true
    ) {
        $data = $this->_checkKey($this->_postData, $key);
        if (is_array($data)) {
            if ($escapeHtml || $trimData) {
                foreach ($data as $key => $value) {
                    $escapeHtml and ($value = htmlspecialchars($value));
                    $trimData and ($value = trim($value));
                    $data[$key] = $value;
                }
            }
            return $data;
        }
        $data = (($data != null) ? $data : $default);
        ($data != null) and $escapeHtml and $data = htmlspecialchars($data);
        ($data != null) and $trimData and $data = trim($data);
        return $data;
    }

    /**
     * This function is used to get the values form SESSION array
     *
     * @param string|null $key     key name
     * @param string|null $default default value
     *
     * @return string|null
     */
    public function session(?string $key, ?string $default = null): ?string
    {
        $data = ($this->_checkKey($this->_sessionData, $key));
        return ($data == null ? $default : $data);
    }

    /**
     * This function is used to get the values form FILE array
     *
     * @param string|null $key     key name
     * @param string|null $default default value
     *
     * @return void
     */
    public function files(?string $key = null, ?string $default = null)
    {
        return $this->_checkKey($this->_fileData, $key);
    }

    /**
     * This function is used to check the given key is exists in the array or not
     * and returns the value if the key is exists else false
     *
     * @param array       $data Data
     * @param string|null $key  Key name
     *
     * @return mixed
     */
    private function _checkKey(
        array $data,
        ?string $key = null
    ) {
        if ($key) {
            if (isset($data[$key])) {
                return $data[$key];
            } else {
                return null;
            }
        }
        return $data;
    }

    /**
     * Returns php://input contents as array
     *
     * @return array
     */
    public function data(): array
    {
        $data = (array) json_decode(file_get_contents('php://input'), true);
        foreach ($data as $key => $value) {
            $data[$key] = htmlspecialchars(trim($value));
        }
        return $data;
    }
}
