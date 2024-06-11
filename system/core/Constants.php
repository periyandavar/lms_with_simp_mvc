<?php
/**
 * Constants class constants are defined here
 * php version 7.3.5
 *
 * @category Constants
 * @package  Core
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */

namespace System\Core;

defined('VALID_REQ') or exit('Invalid request');
/**
 * Constants Class used to access the Constants
 *
 * @category Constants
 * @package  Core
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */
final class Constants
{
    public const METHOD_GET = "get";

    public const METHOD_POST = 'post';

    public const METHOD_PUT = 'put';

    public const METHOD_DELETE = 'delete';

    public const ENV_PRODUCTION = 'production';

    public const ENV_TESTING = 'testing';

    public const ENV_DEVELOPMENT = 'development';

    public const METHOD_PATCH = 'patch';
}
