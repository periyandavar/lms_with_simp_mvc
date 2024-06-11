<?php
/**
 * UserController File Doc Comment
 * php version 7.3.5
 *
 * @category Controller
 * @package  Controller
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */

namespace App\Controller;

defined('VALID_REQ') or exit('Invalid request');
use System\Core\BaseController;
use App\Model\UserModel;
use System\Core\Utility;
use System\Library\FormDataValidation;
use System\Library\Fields;

/**
 * UserController Class Handles the requests by user
 *
 * @category   Controller
 * @package    Controller
 * @subpackage UserController
 * @author     Periyandavar <periyandavar@gmail.com>
 * @license    http://license.com license
 * @link       http://url.com
 */
class UserController extends BaseController
{
    /**
     * Instantiates the new UserController instance
     */
    public function __construct()
    {
        parent::__construct(new UserModel());
    }

    /**
     * Displays the home page for user
     *
     * @return void
     */
    public function getHomePage()
    {
        $this->loadLayout("userHeader.html");
        $this->loadView("userHome");
        $this->loadLayout("userFooter.html");
    }

    /**
     * Displays the user profile
     *
     * @return void
     */
    public function getProfile()
    {
        $id = $this->input->session('id');
        $this->load->model('gender');
        $data['dropdownGen'] = $this->gender->get();
        $data['result'] = $this->model->getProfile($id);
        $this->loadLayout("userHeader.html");
        $this->loadView("userProfile", $data);
        $this->loadLayout("userFooter.html");
    }

    /**
     * Checks the email id is available or not
     *
     * @param string $email email id
     *
     * @return void
     */
    public function isEmailAvailable(string $email)
    {
        $flag = preg_match(
            '/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/',
            $email
        );
        if (!$flag) {
            echo json_encode(["result" => false]);
        } else {
            $result = $this->model->isEmailAvailable($email);
            echo json_encode(["result" => $result]);
        }
    }

    /**
     * Checks the username is available or not
     *
     * @param string $userName username
     *
     * @return void
     */
    public function isNameAvailable(string $userName)
    {
        $result = $this->model->isNameAvailable($userName);
        echo json_encode(["result" => $result]);
    }

    /**
     * Updates the user profile
     *
     * @return void
     */
    public function updateProfile()
    {
        $fdv = new FormDataValidation();
        $this->load->model('gender');
        $genCodes = implode(" ", $this->gender->getCodes());
        $id = $this->input->session('id');
        $fields = new Fields(['gender', 'mobile', 'fullname']);
        $rules = [
            'fullName' => 'alphaSpaceValidation',
            'mobile' => 'mobileNumberValidation',
            'gender' => ["valuesInValidation $genCodes", 'required']
        ];
        $fields->addRule($rules);
        $fields->setRequiredFields('gender', 'mobile', 'fullname');
        $fields->addValues($this->input->post());
        if (!$fdv->validate($fields, $field)) {
            $result["message"] = "Invalid $field..!";
        } elseif (!$this->model->updateProfile($id, $fields->getValues())) {
            $result["message"] = 'Unable to update the profile..!';
        } else {
            $result["message"] = "Profile updated successfully..!";
            $this->log->activity("User updated his profile, user id: '$id'");
        }
        $password = $this->input->post('password', '');
        if ($password != '') {
            if (strlen($password) < 6) {
                $result["message"] .= "Your password is too short & not updated..!";
            } else {
                if (!$this->model->updatePassword($id, $password)) {
                    $result["message"] .= "Unable to update password..!";
                } else {
                    $this->log->activity(
                        "User updated his password, user id: '$id'"
                    );
                }
            }
        }
        echo json_encode($result);
    }

    /**
     * Logout the user
     *
     * @return void
     */
    public function logout()
    {
        session_destroy();
        $this->redirect("login");
    }

    /**
     * Displays the lent books of the user
     *
     * @return void
     */
    public function getLentBooks()
    {
        $offset = $this->input->get("index") ?? 0;
        $limit = $this->input->get("limit") ?? 5;
        $search = $this->input->get("search");
        $user = $this->input->session('id');
        $data["books"] = $this->model->getLentBooks(
            $user,
            $tCount,
            $offset,
            $limit,
            $search
        );
        $data['pagination'] = [
            "tcount" => $tCount,
            "cpage" => floor($offset/$limit),
            "tpages" => ceil($tCount/$limit),
            "start" => $offset + 1,
            "end" => $offset + count($data['books']),
            "limit" => $limit,
            "search" => $search,
        ];
        $this->loadLayout("userHeader.html");
        $this->loadView("lentBooks", $data);
        $this->loadLayout("userFooter.html");
    }

    /**
     * Displays the books requested by the user
     *
     * @return void
     */
    public function getRequestedBooks()
    {
        $offset = $this->input->get("index") ?? 0;
        $limit = $this->input->get("limit") ?? 5;
        $search = $this->input->get("search");
        $user = $this->input->session('id');
        $limit = $limit == 0 ? 5 : $limit;
        $data["books"] = $this->model->getRequestedBooks(
            $user,
            $tCount,
            $offset,
            $limit,
            $search
        );
        $data['pagination'] = [
            "tcount" => $tCount,
            "cpage" => floor($offset/$limit),
            "tpages" => ceil($tCount/$limit),
            "start" => $offset + 1,
            "end" => $offset + count($data['books']),
            "limit" => $limit,
            "search" => $search,
        ];
        $this->loadLayout("userHeader.html");
        $this->loadView("booked", $data);
        $this->loadLayout("userFooter.html");
    }

    /**
     * Removes the user book request
     *
     * @param integer $id userRequestId
     *
     * @return void
     */
    public function removeRequest(int $id)
    {
        $user = $this->input->session('id');
        if ($result['result'] = $this->model->removeRequest($id, $user)) {
            $this->log->activity("User removed his request($id), user id: '$id'");
        }
        echo json_encode($result);
    }

    /**
     * Change user Password
     *
     * @return void
     */
    public function changePassword()
    {
        $msg = '';
        $password = $this->input->post('password', '');
        $user = $this->input->session('urole', '');
        $id = $this->input->session('uid', '');
        if ($password != '' && $user != '') {
            if (strlen($password) < 6) {
                $msg = "toast('Your password is too short & not updated..!',"
                        .  "'danger', 'Failed');";
            } else {
                if (!$this->model->updatePassword($id, $password, $user)) {
                    $msg = "toast('Unable to update password..!', 'danger',"
                        . " 'Failed');";
                } else {
                    $this->log->activity(
                        "User updated his password, user id: '$id'"
                    );
                    Utility::setSessionData('urole', null);
                    Utility::setSessionData('uid', null);
                    Utility::setSessionData(
                        'msg',
                        "toast('Password changed successfully, Now "
                        . "login with your new password..!')"
                    );
                    if ($user == ADMIN_USER) {
                        $this->redirect("admin/login");
                    } else {
                        $this->redirect("login");
                    }
                }
            }
        } else {
            $msg = "toast('Invalid Request..!')";
        }
        Utility::setSessionData('msg', $msg);
        $this->redirect("");
    }
}
