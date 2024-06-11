<?php
/**
 * FrameworkExcepion
 * php version 7.3.5
 *
 * @category   FrameworkExcepion
 * @package    Core
 * @subpackage Exception
 * @author     Periyandavar <periyandavar@gmail.com>
 * @license    http://license.com license
 * @link       http://url.com
 */

namespace System\Core;

defined('VALID_REQ') or exit('Invalid request');
/**
 * FrameworkExcepion raised when there is an excptions occured other than application
 *
 * @category   FrameworkExcepion
 * @package    Core
 * @subpackage Excepion
 * @author     Periyandavar <periyandavar@gmail.com>
 * @license    http://license.com license
 * @link       http://url.com
 */
class FrameworkException extends \Exception
{
    /**
     * Instantiate new FrameworkException instance
     *
     * @param string    $message  Message
     * @param integer   $code     Code
     * @param Throwable $previous Previous exception
     */
    public function __construct(
        $message = "Framework Exception",
        $code = 0,
        Throwable $previous  = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Returns exception details
     *
     * @return string
     */
    public function __toString(): string
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
