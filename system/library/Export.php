<?php
/**
 * Exporter
 * php version 7.3.5
 *
 * @category Exporter
 * @package  Library
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */

namespace System\Library;

use System\Core\Log;

defined('VALID_REQ') or exit('Invalid request');
/**
 * Exporter Class used to store the input Exporter
 * User defined Error controller should implement this interface
 *
 * @category Exporter
 * @package  Library
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */
class Export
{
    private $_exporter;

    /**
     * Instantiate new Export instance
     *
     * @param string $type Export type
     */
    public function __construct(string $type)
    {
        $file = 'system/library/export/' . $type .'Exporter.php';
        $class = $type . 'Exporter';
        if (file_exists($file)) {
            include_once "$file";
            $class = "\\System\\Libraray\\Export\\".$class;
            $this->_exporter = new $class();
        } else {
            Log::getInstance()->Debug("Invalid export type '$file'");
        }
    }

    /**
     * Generates the export file
     *
     * @param array      $data   Data
     * @param null|array $ignore Ignore values
     *
     * @return void
     */
    public function generate(array $data, ?array $ignore)
    {
        $this->_exporter->generate($data, $ignore);
    }

    /**
     * Sends the export file
     *
     * @return void
     */
    public function send()
    {
        $this->_exporter->send();
    }

    /**
     * Store the export file
     *
     * @param string $destination Destination with filename
     *
     * @return void
     */
    public function store(string $destination)
    {
        $this->_exporter->store($destination);
    }
}
