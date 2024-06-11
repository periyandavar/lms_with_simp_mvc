<?php
/**
 * BookService File Doc Comment
 * php version 7.3.5
 *
 * @category Service
 * @package  Service
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */

namespace App\Service;

defined('VALID_REQ') or exit('Invalid request');
use System\Core\BaseService;

/**
 * BookService Class Handles the BookService class Logical operations
 *
 * @category   Service
 * @package    Service
 * @subpackage BookService
 * @author     Periyandavar <periyandavar@gmail.com>
 * @license    http://license.com license
 * @link       http://url.com
 */

class BookService extends BaseService
{
    /**
     * Seperates the issued and requested users
     *
     * @param array $users User, status
     *
     * @return array
     */
    public function seperateUsers(array $users): object
    {
        $issuedUsers = [];
        $requestedUsers = [];
        foreach ($users as $user) {
            if ($user->status == STATUS_ISSUED) {
                $issuedUsers[] = $user->username;
            } else {
                $requestedUsers[] = $user->username;
            }
        }
        $issuedUsers['issued'] = $issuedUsers;
        $issuedUsers['requested'] = $requestedUsers;
        return $this->toObject($issuedUsers);
    }
}
