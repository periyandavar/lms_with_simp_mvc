<?php
/**
 * Cache
 * php version 7.3.5
 *
 * @category Cache
 * @package  Library
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */

namespace System\Core;

defined('VALID_REQ') or exit('Invalid request');

/**
 * Fields Class used to store the input fields
 * User defined Error controller should implement this interface
 *
 * @category Cache
 * @package  Library
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */
class Cache
{
    private $_file;

    private $_dir;

    private $_cachetime;

    /**
     * Instantiate new Cache instance
     *
     * @param string $file Filename
     */
    public function __construct($file)
    {
        $file = str_replace("/", ".", trim($file, "/"));
        $dir = ($config['cache'] ?? 'system/cache/');
        $this->_file = $dir . $file . '.cache.html';
        !is_dir($dir) and mkdir($dir, 0777);   
    }

    /**
     * Sends the cache file if exists
     *
     * @return void
     */
    public function cache()
    {
        $cachetime = 18000;
        if (file_exists($this->_file) && time() - $cachetime < filemtime($this->_file)) {
            readfile($this->_file);
            exit;
        }
        echo "assa";
    }

    /**
     * Creates the cache file
     * 
     * @param string|null $content Content
     *
     * @return void
     */
    public function store(?string $content = null)
    {
        $content = $content ?? ob_get_contents();
        $cache = fopen($this->_file, 'w');
        $content = "<!-- Cached copy, generated ".date('H:i', filemtime($this->_file))." -->\n" . $content;
        fwrite($cache, $content);
        fclose($cache);
    }
}
