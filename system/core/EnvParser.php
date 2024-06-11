<?php
/**
 * EnvParser
 * php version 7.3.5
 *
 * @category EnvParser
 * @package  Core
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */

namespace System\Core;

use System\Core\FrameworkException;

defined('VALID_REQ') or exit('Invalid request');
/**
 * EnvParser parse the env files and loads values from it
 *
 * @category EnvParser
 * @package  Core
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */
class EnvParser
{
    /**
     * Env File location
     */
    private $_env;

    /**
     * Instantitate the new EnvParser Instance
     *
     * @param $file ENV File Name
     *
     * @throws FrameworkException
     */
    public function __construct($file)
    {
        if (file_exists($file)) {
            $this->_env = $file;
        } else {
            throw new FrameWorkException("Unable to locate ENV file");
        }
    }

    /**
     * Loads env file values from .env file and add to $_ENV
     *
     * @return void
     */
    public function load()
    {
        $contents = file($this->_env, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($contents as $line) {
            if (strpos(trim($line), "#") !== false) {
                continue;
            }
            putenv($line);
        }
    }
}
