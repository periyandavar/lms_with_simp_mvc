<?php
/**
 * RecordController File Doc Comment
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
use App\Model\RecordModel;
use Firebase\JWT\JWT;

/**
 * RecordController Class Handles the requests by user
 *
 * @category   Controller
 * @package    Controller
 * @subpackage RecordController
 * @author     Periyandavar <periyandavar@gmail.com>
 * @license    http://license.com license
 * @link       http://url.com
 */
class RecordController extends BaseRestController
{
    /**
     * Instantiate the new HomeController Instance
     */
    public function __construct()
    {
        parent::__construct(new RecordModel());
    }

    /**
     * GET request
     *
     * @return void
     */
    public function get()
    {
        $secretKey = "C88F2A08C1A70E2D44CCA020CE9394569CEF9E8C4A58E2D2D1BCCFC168C116A4"; //secret key
        $jwt = null;
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? "";
        $arr = explode(" ", $authHeader);
        $jwt = $arr[1] ?? null;
        if ($jwt) {
            try {
                $decoded = JWT::decode($jwt, $secretKey, array('HS256'));
                // echo json_encode(
                //     array(
                //     "message" => "Access granted:",
                //     )
                // );
                $id = func_get_args()[0] ?? null;
                $result = $this->model->get($id);
                echo json_encode(["result" => $result]);
            } catch (Exception|Error|\Firebase\JWT\ExpiredException $e) {
                http_response_code(401);
                echo json_encode(
                    array(
                    "message" => "Access denied.",
                    "error" => $e->getMessage()
                    )
                );
            }
            return;
        }
        echo json_encode(
            array(
            "message" => "token not found.",
            )
        );
    }

    /**
     * POST request
     *
     * @return void
     */
    public function create()
    {
        $fields = $this->input->post();
        $result = $this->model->insert($fields) ? 'success' : 'failed';
        echo json_encode(["result" => $result]);
    }

    /**
     * PUT request
     *
     * @return void
     */
    public function update()
    {
        $id = func_get_arg(0);
        $fields = $this->input->data();
        $result = $this->model->update($id, $fields) ? 'success' : 'failed';
        echo json_encode(["result" => $result]);
    }

    /**
     * Patch request
     *
     * @return void
     */
    public function patch()
    {
        $id = func_get_arg(0);
        $fields = $this->input->data();
        $result = $this->model->update($id, $fields) ? 'success' : 'failed';
        echo json_encode(["result" => $result]);
    }

    /**
     * Delete Request
     *
     * @return void
     */
    public function delete()
    {
        $id = func_get_arg(0);
        $result = $this->model->delete($id) ? 'success' : 'failed';
        echo json_encode(["result" => $result]);
    }
}
