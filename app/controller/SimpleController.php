<?php
/**
 * SimpleController File Doc Comment
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
use System\Core\BaseRestController;
use App\Model\SimpleModel;
use System\Library\FormDataValidation;
use System\Library\Fields;
use Firebase\JWT\JWT;

/**
 * SimpleController Class Handles the requests by user
 *
 * @category   Controller
 * @package    Controller
 * @subpackage SimpleController
 * @author     Periyandavar <periyandavar@gmail.com>
 * @license    http://license.com license
 * @link       http://url.com
 */
class SimpleController extends BaseRestController
{
    /**
     * Instantiate the new HomeController Instance
     */
    public function __construct()
    {
        parent::__construct(new SimpleModel());
    }

    // /**
    //  * Post request
    //  *
    //  * @return void
    //  */
    // public function create()
    // {
    //     $data = [];
    //     $fdv = new FormDataValidation();
    //     $this->load->model('gender');
    //     $genCodes = implode(" ", $this->gender->getCodes());
    //     $fields = new fields(
    //         [
    //             'email',
    //             'username',
    //             'fullname',
    //             'password',
    //             'gender',
    //             'mobile'
    //         ]
    //     );
    //     $rules = [
    //         'email' => ['emailValidation', 'required'],
    //         'fullname' => ['alphaSpaceValidation', 'required'],
    //         'username' => ["expressValidation /^[A-Za-z0-9_]*$/", 'required'],
    //         'password' => ["lengthValidation 6", 'required'],
    //         'mobile' => ['mobileNumberValidation', 'required'],
    //         'gender' => ["valuesInValidation $genCodes", 'required']
    //     ];
    //     $fields->addRule($rules);
    //     $fields->addValues($this->input->data());
    //     if (!$fdv->validate($fields, $field)) {
    //         $flag = 0;
    //         $msg = "Invalid $field..!";
    //     } elseif (!$this->model->register($fields->getValues())) {
    //         $flag = 0;
    //         $msg = "Unable to create an account..!";
    //     } else {
    //         $flag = 1;
    //         $msg = "Your Account is created successfully..!";
    //         $this->log->activity(
    //             "A new account created with values "
    //             . json_encode($fields->getValues())
    //         );
    //     }
    //     $result = ['result'=> $flag, 'message' => $msg];
    //     echo json_encode($result);
    // }

    /**
     * Performs user login
     *
     * @return void
     */
    public function create()
    {
        $data = [];
        $username = $this->input->data()['username'];
        $fdv = new FormDataValidation();
        $fields = new fields(['username']);
        $fields->addRule(
            [
            'username' => [
                "expressValidation /^[A-Za-z0-9_]*$/",
                'required'
                ]
            ]
        );
        $fields->addValues($this->input->data());
        if (!$fdv->validate($fields, $field)) {
            $msg = "Invalid $field..!";
        } else {
            $user = $this->model->getUser($username);
            if (isset($user)
                && $user->password == md5($this->input->data()['password'])
            ) {
                $secretKey = "C88F2A08C1A70E2D44CCA020CE9394569CEF9E8C4A58E2D2D1BCCFC168C116A4"; //secret key
                $issuer = "LMS"; // issuer
                $issuedAt = time(); // issued at
                $nbf = $issuedAt + 10; //not before in seconds
                $expireAt = $issuedAt + 120; // expire time in 120 seconds
                $token = array(
                    "iss" => $issuer,
                    "iat" => $issuedAt,
                    "nbf" => $nbf,
                    "exp" => $expireAt,
                    "data" => array(
                        "id" => $user->id,
                ));
                http_response_code(200);

                $jwt = JWT::encode($token, $secretKey);
                echo json_encode(
                    array(
                    "message" => "Successful login.",
                    "jwt" => $jwt,
                    "id" => $user->id,
                    "expireAt" => $expireAt
                    )
                );
                return;
            } else {
                $msg = "Login failed..!";
            }
        }
        $result = ['result'=> 0, 'message' => $msg];
        echo json_encode($result);
    }
}
