<?php
/**
 * History Helper
 * php version 7.3.5
 *
 * @category HistoryHelper
 * @package  Helper
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */

namespace System\Helper;

defined('VALID_REQ') or exit('Invalid request');
/**
 * History Helper
 * php version 7.3.5
 *
 * @category HistoryHelper
 * @package  Helper
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */
class HistoryHelper
{
    /**
     * Stores last 7 accessed URL in cookies
     *
     * @return void
     */
    public static function traceUser()
    {
        global $config;
        $history = [];
        $cookieExpiration = $config['cookie_expiration'];
        $cookieName = "history";
        (isset($_COOKIE[$cookieName])) and
            $history = json_decode($_COOKIE[$cookieName], true);
        array_push(
            $history,
            Utility::currentUrl()
        );
        if (count($history) > 7) {
            array_splice($history, 0, count($history)-7);
        }
        $history = json_encode($history);
        setcookie($cookieName, $history, time() + ($cookieExpiration), "/");
    }
    /**
     * Retrieve histories in cookies
     *
     * @return void
     */
    public static function getHistory()
    {
        $cookieName = "history";
        $history = (isset($_COOKIE[$cookieName]))
            ? json_decode($_COOKIE[$cookieName], true) : null;
        return $history;
    }
}
