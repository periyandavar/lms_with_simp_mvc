<?php
/**
 * Exporter File Doc Comment
 * php version 7.3.5
 *
 * @category Exporter
 * @package  Exporter
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */

namespace System\Libraray\Export;

defined('VALID_REQ') or exit('Invalid request');

/**
 * Exporter Class used to store the input Exporter
 * User defined Error controller should implement this interface
 *
 * @category Exporter
 * @package  Exporter
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */
interface Exporter
{
    /**
     * Calls exporter generate function
     *
     * @param array      $data   Data
     * @param null|array $ignore Ignore values
     *
     * @return void
     */
    public function generate(array $data, ?array $ignore);

    /**
     * Send csv file to the client
     *
     * @return void
     */
    public function send();

    /**
     * Stores the excel file on the server
     *
     * @param string $destination Destination with filename
     *
     * @return void
     */
    public function store(string $destination);
}
