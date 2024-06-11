<?php
/**
 * AdminController File Doc Comment
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
use App\Model\AdminModel;
use System\Core\Utility;
use System\Library\FormDataValidation;
use System\Library\Fields;

/**
 * AdminController Class Handles the admin functionalities
 *
 * @category   Controller
 * @package    Controller
 * @subpackage AdminController
 * @author     Periyandavar <periyandavar@gmail.com>
 * @license    http://license.com license
 * @link       http://url.com
 */
class AdminController extends BaseController
{
    /**
     * Instantiate a new AdminController instance.
     */
    public function __construct()
    {
        parent::__construct(new AdminModel());
    }

    /**
     * Displays the login page for admin_user
     *
     * @return void
     */
    public function login()
    {
        $this->loadView("admin");
        if ($this->input->session('msg') != null) {
            $this->addScript($this->input->session('msg'));
            Utility::setSessionData('msg', null);
        }
    }

    /**
     * Performs admin_user login
     *
     * @return void
     */
    public function doLogin()
    {
        $user = $this->input->post('email');
        $captcha = $this->input->post("verfcode");
        if ($captcha != $this->input->session("captcha")) {
            $data["msg"] = "Invalid captcha..!";
            $this->loadView("admin", $data);
            return;
        }
        $result = $this->model->getAdminUser($user);
        if ($result != null) {
            if ($result->password == md5($this->input->post('password'))) {
                Utility::setsessionData('login', true);
                Utility::setSessionData("type", $result->type);
                Utility::setSessionData("id", $result->id);
                $this->redirect(strtolower($result->type) . "/home");
            }
        }
        $data["msg"] = "Login failed..!";
        $this->loadView("admin", $data);
    }


    /**
     * Handle the home page request
     *
     * @return void
     */
    public function getHomePage()
    {
        $user = $this->input->session('type');
        $this->loadLayout($user . "Header.html");
        $this->loadView($user. 'home');
        $this->loadLayout($user . "Footer.html");
    }

    /**
     * Handle the profile page request
     *
     * @return void
     */
    public function getProfile()
    {
        $user = $this->input->session('type');
        $id = $this->input->session('id');
        $data['result'] = $this->model->getProfile($id);
        $this->loadLayout($user . "Header.html");
        $this->loadView($user . 'Profile', $data);
        $this->loadLayout($user . "Footer.html");
    }

    /**
     * Handle logout request
     *
     * @return void
     */
    public function logout()
    {
        session_destroy();
        $this->redirect("admin/login");
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
     * Update the admin profile
     *
     * @return void
     */
    public function updateProfile()
    {
        $fdv = new FormDataValidation();
        $id = $this->input->session('id');
        $adminId = $this->input->session('id');
        $user = $this->input->session('type');
        $fields = new Fields(['fullName']);
        $rules = [
            'fullName' => 'alphaSpaceValidation',
        ];
        $fields->addRule($rules);
        $fields->setRequiredFields('fullName');
        $fields->addValues($this->input->post());
        if (!$fdv->validate($fields, $field)) {
            $result['message'] = "Invalid $field..!";
        } elseif (!$this->model->updateProfile($id, $fields->getValues())) {
            $result['message'] = "Unable to update the profile..!";
        } else {
            $result['message'] = "Profile updated successfully..!";
            $this->log->activity(
                "Admin user updated his profile with new values "
                . json_encode($fields->getValues()) . ", admin id: '$adminId'"
            );
        }
        $password = $this->input->post('password');
        if ($password != '') {
            if (strlen($password) < 6) {
                $result['message'] .= "your password is too short & not updated..!";
            } else {
                if (!$this->model->updatePassword($id, $password)) {
                    $result['message'] .= "but Unable to update password..!";
                }
                $this->log->activity(
                    "Admin user updated his password admin id: '$adminId'"
                );
            }
        }
        echo json_encode($result);
    }

    /**
     * Handle the request to the settings page
     *
     * @return void
     */
    public function getSettings()
    {
        $data['data'] = $this->model->getConfigs();
        $this->loadLayout("adminHeader.html");
        $this->loadView("settings", $data);
        $this->loadLayout("adminFooter.html");
    }

    /**
     * Update the settings
     *
     * @return void
     */
    public function updateSettings()
    {
        $fdv = new FormDataValidation();
        $adminId = $this->input->session('id');
        $fields = new Fields(
            ['maxBookLend', 'maxLendDays', 'maxBookRequest', 'fineAmtPerDay']
        );
        $fields->setRequiredFields(
            'maxBookLend',
            'maxLendDays',
            'maxBookRequest',
            'fineAmtPerDay'
        );
        $rules = [
            'maxBookLend' => 'numericValidation 1',
            'maxLendDays' => 'numericValidation 1',
            'maxBookRequest' => 'numericValidation 1',
            'fineAmtPerDay' => 'numericValidation 1'
        ];
        $fields->addRule($rules);
        $fields->addValues($this->input->post());
        if (!$fdv->validate($fields, $field)) {
            $result['message'] = "Invalid $field..!";
        } elseif (!$this->model->updateSettings($fields->getValues())) {
            $result['message'] = "Unable to update the settings..!";
        } else {
            $result['message'] = "Settings updated successfully..!";
            $this->log->activity(
                "Admin user updated lms settings with new values: "
                . json_encode($fields->getValues()) .", admin id: '$adminId'"
            );
        }
        echo json_encode($result);
    }

    /**
     * Loads the cms contents
     *
     * @return void
     */
    public function getCms()
    {
        $data['data'] = $this->model->getCmsConfigs();
        $this->loadLayout("adminHeader.html");
        $this->loadView("cms", $data);
        $this->loadLayout("adminFooter.html");
    }

    /**
     * Update the cms contents
     *
     * @return void
     */
    public function updateCms()
    {
        $fdv = new FormDataValidation();
        $adminId = $this->input->session('id');
        $fields = [
            'aboutus',
            'address',
            'email',
            'mobile',
            'vision',
            'mission'];
        $fields = new Fields($fields);
        $fields->setRequiredFields(
            'aboutus',
            'address',
            'email',
            'vision',
            'mission',
            'mobile'
        );
        $rules = [
            'mobile' => "landlineValidation",
            'email' => 'emailValidation'
        ];
        $fields->addRule($rules);
        $fields->addValues($this->input->post());
        if (!$fdv->validate($fields, $field)) {
            $result['message'] = "Invalid $field..!";
        } elseif (!$this->model->updateCmsConfigs($fields->getValues())) {
            $result['message'] = "Unable to update the settings..!";
        } else {
            $result['message'] = "Contents updated successfully..!";
            $this->log->activity(
                "Admin user updated cms settings with new values "
                . json_encode($fields->getValues()) . ", admin id: '$adminId'"
            );
        }
        echo json_encode($result);
    }

    /**
     * Displays forgot-password page
     *
     * @return void
     */
    public function forgotPassword()
    {
        $this->loadView("adminForgetPassword");
        if ($this->input->session('msg') != null) {
            $this->addScript($this->input->session('msg'));
            Utility::setSessionData('msg', null);
        }
    }

    /**
     * Recover user Account
     *
     * @return void
     */
    public function recoveryRequest()
    {
        $this->load->library('captcha');
        $this->load->library('mailer');
        $this->load->model('home');
        $data['footer'] = $this->home->getFooterData();
        $email = $this->input->post('email', '');
        if (!((new FormDataValidation())->emailValidation($email))) {
            Utility::setSessionData('msg', "toast('Invalid email id..!')");
            $this->redirect("admin/forgot-password");
        }
        $user = $this->model->getAdminUser($email);
        if ($user == null) {
            Utility::setSessionData('msg', "toast('User account not found..!')");
            $this->redirect("admin/forgot-password");
        }
        $record['userId'] = $user->id;
        $record['token'] = md5(time().$this->captcha->randomStr(7));
        $record['expireAt'] = date('Y-m-d H:i:s', strtotime('now +12 minutes'));
        $record['role'] = ADMIN_USER;
        $mailData['user'] = $user->fullName;
        $mailData['link'] = Utility::baseUrl() . "/recover-account?token="
            . $record['token'];
        $flag = $this->home->addPassRest($record)
            && $this->mailer->send(
                'lms@lms.com',
                $email,
                "Recover LMS account",
                'mailcontents.html',
                $mailData
            );
        $result['flag'] = $flag;
        $this->loadLayout("header.html");
        $this->loadView("userForgetPassword", ["flag"=>$flag]);
        $this->loadView("footer", $data);
    }
}
