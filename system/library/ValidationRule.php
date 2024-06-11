<?php
/**
 * Validation Rule File Doc Comment
 * php version 7.3.5
 *
 * @category ValidationRule
 * @package  ValidationRule
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */

namespace System\Library;

defined('VALID_REQ') or exit('Invalid request');

/**
 * ValidationRule Interface used to define custom validation
 *
 * @category ValidationRule
 * @package  ValidationRule
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */
interface ValidationRule
{
    /**
     * Custom validation
     *
     * @param string $data Data to be validated
     * @param string $msg  String reference where the message will be stored
     *
     * @return boolean|null
     */
    public function validate(string $data, string &$msg): ?bool;
}
