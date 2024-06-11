<?php
/**
 * AdminModel File Doc Comment
 * php version 7.3.5
 *
 * @category Model
 * @package  Model
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */

namespace App\Model;

defined('VALID_REQ') or exit('Invalid request');
use System\Core\BaseModel;

/**
 * AdminModel Class Handles the AdminController class data base operations
 *
 * @category   Model
 * @package    Model
 * @subpackage AdminModel
 * @author     Periyandavar <periyandavar@gmail.com>
 * @license    http://license.com license
 * @link       http://url.com
 */
class AdminModel extends BaseModel
{
    /**
     * Returns the admin details
     *
     * @param int $id Admin Id
     *
     * @return object|null
     */
    public function getProfile(int $id): ?object
    {
        $this->db->select('fullName', 'email')
            ->selectAs("date_format(updatedat, '%d-%m-%Y %h:%i:%s') updatedAt")
            ->from('admin_user')
            ->where('id', '=', $id);
        $this->db->where('deletionToken', '=', DEFAULT_DELETION_TOKEN)->execute();
        $admin = $this->db->fetch() or $admin = null;
        return $admin;
    }

    /**
     * Check the user is exiting with the given mail id or not
     *
     * @param string $email email id
     *
     * @return boolean
     */
    public function isEmailAvailable(string $email): bool
    {
        $this->db->select("id")
            ->from('admin_user')
            ->where('email', '=', $email)
            ->where('deletionToken', '=', DEFAULT_DELETION_TOKEN)
            ->execute();
        if ($this->db->fetch()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Updates the admin details
     *
     * @param int   $id       Admin Id
     * @param array $userData details
     *
     * @return bool
     */
    public function updateProfile(int $id, array $userData): bool
    {
        $result = $this->db->update('admin_user', $userData)
            ->where('id', '=', $id)
            ->execute();
        return $result;
    }

    /**
     * Updates the admin password
     *
     * @param int    $id       Admin Id
     * @param string $password Admin New Password
     *
     * @return bool
     */
    public function updatePassword(int $id, string $password): bool
    {
        $result = $this->db->update('admin_user', ['password' => md5($password)])
            ->where('id', '=', $id)
            ->execute();
        return $result;
    }

    /**
     * Returns the details of the admin_user
     *
     * @param string $email Email Id
     *
     * @return object|null
     */
    public function getAdminUser(string $email): ?object
    {
        $this->db->select("admin_user.id", "password", "fullName", "role.value type")
            ->from('admin_user')
            ->innerJoin('role')
            ->on('admin_user.role=role.code');
        $this->db->where('email', '=', $email);
        $this->db->where('admin_user.deletionToken', '=', DEFAULT_DELETION_TOKEN)
            ->execute();
        $admin = $this->db->fetch() or $admin = null;
        return $admin;
    }

    /**
     * Returns core configuration details
     *
     * @return object|null
     */
    public function getConfigs(): ?object
    {
        $this->db->select(
            "maxBookLend",
            "maxLendDays",
            "fineAmtPerDay",
            "maxBookRequest",
        )->selectAs(
            "date_format(updatedAt, '%d-%m-%Y %h:%i:%s') updatedAt"
        )->from("core_config")->where('id', '=', 1)->execute();
        ($result = $this->db->fetch()) or $result = null;
        return $result;
    }

    /**
     * Returns the cms details
     *
     * @return object|null
     */
    public function getCmsConfigs(): ?object
    {
        $this->db->select(
            "aboutUs",
            "address",
            "mobile",
            "email",
            "vision",
            "mission",
        )->selectAs(
            "date_format(updatedAt, '%d-%m-%Y %h:%i:%s') updatedAt"
        )->from("cms")->where('id', '=', 1)->execute();
        ($result = $this->db->fetch()) or $result = null;
        return $result;
    }

    /**
     * Updates the core config details
     *
     * @param array $data core configs
     *
     * @return boolean
     */
    public function updateSettings(array $data): bool
    {
        $this->db->update('core_config', $data)->where('id', '=', 1);
        return $this->db->execute();
    }

    /**
     * Updates the cms details
     *
     * @param array $data cms details
     *
     * @return boolean
     */
    public function updateCmsConfigs(array $data): bool
    {
        return $this->db->update('cms', $data)->where('id', '=', 1)->execute();
    }
}
