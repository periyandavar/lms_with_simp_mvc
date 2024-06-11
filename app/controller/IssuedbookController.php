<?php
/**
 * IssuedBookController File Doc Comment
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
use App\Model\IssuedBookModel;
use System\Core\Utility;
use System\Library\FormDataValidation;
use System\Library\Fields;
use App\Service\IssuedBookService;

/**
 * IssuedBookController Class Handles Issued books
 *
 * @category   Controller
 * @package    Controller
 * @subpackage IssuedBookController
 * @author     Periyandavar <periyandavar@gmail.com>
 * @license    http://license.com license
 * @link       http://url.com
 */

class IssuedBookController extends BaseController
{
    /**
     * Instantiate the new IssuedBookController instance
     */
    public function __construct()
    {
        parent::__construct(new IssuedBookModel(), new IssuedBookService());
    }

    /**
     * Displays the list of issued books with the option to add new issue book entry
     *
     * @return void
     */
    public function issue()
    {
        $user = $this->input->session('type');
        $this->loadLayout($user . "Header.html");
        $this->includeScript("issuedbook.js");
        $this->loadView('manageissuedbooks');
        $this->loadLayout($user . "Footer.html");
        if ($this->input->session('msg') != null) {
            $this->addScript($this->input->session('msg'));
            Utility::setSessionData('msg', null);
        }
    }

    /**
     * Marks the issued book as returned
     *
     * @param integer $id IssuedBookId
     *
     * @return void
     */
    public function markAsReturn(int $id)
    {
        $adminId = $this->input->session('id');
        if (($result['result'] = $this->model->bookReturned($id))) {
            $this->log->activity(
                "The issued_book($id) is marked as returned, admin id: '$adminId'"
            );
        }
        echo json_encode($result);
    }

    /**
     * Displays the page to manage user requests
     *
     * @return void
     */
    public function manageUserRequest()
    {
        $user = $this->input->session('type');
        $this->loadLayout($user . "Header.html");
        $this->includeScript("issuedbook.js");
        $this->loadView('manageUserRequest');
        $this->loadLayout($user . "Footer.html");
        if ($this->input->session('msg') != null) {
            $this->addScript("toast('" . $this->input->session('msg') . "')");
            Utility::setSessionData('msg', null);
        }
    }
    /**
     * Displays the user details of the given book by Id in JSON
     *
     * @param string $userId UserId
     *
     * @return void
     */
    public function getUserDetails(string $userId)
    {
        $result = $this->model->getUserDetails($userId);
        $result->condition = $this->service->checkUserCondition(
            $result,
            $this->model->getMaxBooksToLend()
        );
        echo json_encode($result);
    }
    /**
     * Manage the user request
     *
     * @param integer $id RequestId
     *
     * @return void
     */
    public function manageRequest(int $id)
    {
        $user = $this->input->session('type');
        $result = $this->model->getRequestDetails($id);
        if ($result == null) {
            Utility::setSessionData('msg', 'Invalid Action');
            $this->redirect('request-management');
        }
        $result->lent = $this->model->lentBooksCount($result->userId);
        $max = $this->model->getMaxBooksToLend($result->userId);
        if ($max <= $result->lent) {
            $result->msg = "The user almost lent maximum number of books";
        } elseif ($result->available == 0) {
            $result->msg = "Book is currently not available";
        }
        $data['data'] = $result;
        $this->loadLayout($user . "Header.html");
        $this->includeScript("issuedbook.js");
        $this->loadView('userRequest', $data);
        $this->loadLayout($user . "Footer.html");
    }

    /**
     * Update the details of the user request
     *
     * @param integer $id requestId
     *
     * @return void
     */
    public function updateRequest(int $id)
    {
        $updateTo = $this->input->post('status');
        $adminId = $this->input->session('id');
        if ($flag = $this->model->updateRequest(
            $id,
            $updateTo,
            $this->input->post('comments', '')
        )
        ) {
            $this->log->activity(
                "The user request($id) is updated with new values "
                . json_encode(
                    [
                        "status" => $updateTo,
                        "comment" => $this->input->post('comments', '')
                    ]
                ) . ", admin id: '$adminId'"
            );
        }

        $script = $flag == true ? 'Success..!' : 'Failed..!';
        Utility::setSessionData('msg', $script);
        $this->redirect('request-management');
    }

    /**
     * Adds new issue book entry
     *
     * @return void
     */
    public function add()
    {
        $fdv = new FormDataValidation();
        $user = $this->input->session('type');
        $adminId = $this->input->session('id');
        $fields = new Fields(['userId', 'bookId', 'comments']);
        $rules = [
            'userId' => 'numericValidation 0',
            'bookId' => 'numericValidation 0'
        ];
        $fields->addRule($rules);
        $fields->setRequiredFields('userId', 'bookId');
        $fields->addValues($this->input->post());
        $flag = $fdv->validate($fields, $field);
        if (!$fdv->validate($fields, $field)) {
            $script = "toast('Invalid $field..!')";
        } else {
            $values = $fields->getValues();
            $userDetail = $this->model->getUserDetails($values['userId']);
            $bookDetail = $this->model->getBookDetails($values['bookId']);
            $maxLendBook = $this->model->getMaxBooksToLend();
            if (!$this->service->checkLendCondition(
                $userDetail,
                $bookDetail,
                $maxLendBook,
                $msg
            )
            ) {
                $script = "toast('$msg..!', 'danger', 'Failed')";
            } elseif (!$this->model->addIssuedBook($values)) {
                $script = "toast('The user alredy lend a copy of this book..!'";
                $script .= ",'danger', 'Failed')";
            } else {
                $this->log->activity(
                    "Admin user issued a new book "
                    . json_encode($values) . ", admin id: '$adminId'"
                );
                $script = "toast('New Entry Added Successfully..!','success')";
            }
        }
        $issuedBooks = $this->model->getIssuedBooks();
        $fineSettings = $this->model->getFineConfigs();
        $data['issuedBooks'] = $this->service->calculateFine(
            $issuedBooks,
            $fineSettings
        );
        Utility::setSessionData('msg', $script);
        $this->redirect('issued-book-management');
    }

    /**
     * Adds new book request by user
     *
     * @param integer $id BookId
     *
     * @return void
     */
    public function request(int $id)
    {
        $msg = null;
        $result['result'] = 0;
        $user = $this->input->session('id');
        $flag = $this->service->checkRequestCondition(
            $this->model->getUserDetails($user),
            $this->model->getBookDetails($id),
            $this->model->getMaxVals(),
            $msg
        );
        if ($flag) {
            if (!$this->model->requestBook($user, $id)) {
                $msg = "You already requested this book";
            } else {
                $this->log->activity(
                    "User requestd a new book($id), user id: '$user'"
                );
                $msg = "success..!";
                $result['result'] = 1;
            }
        }
        $result['msg'] = $msg;
        echo json_encode($result);
    }

    /**
     * Loads Issued books
     *
     * @return void
     */
    public function loadIssuedBook()
    {
        $start = $this->input->get("iDisplayStart", '0');
        $limit = $this->input->get("iDisplayLength", '10');
        $sortby = $this->input->get("iSortCol_0", '0');
        $sortDir = $this->input->get("sSortDir_0", 'ASC');
        $searchKey = $this->input->get("sSearch");
        $tcount = $tfcount = '';
        if ($sortby == 0) {
            $sortby = "ReturnedAt";
            $sortDir = "DESC";
        }
        $issuedBooks = $this->model->getIssuedBooks(
            $start,
            $limit,
            $sortby,
            $sortDir,
            $searchKey,
            $tcount,
            $tfcount
        );
        $fineSettings = $this->model->getFineConfigs();
        $data['aaData'] = $this->service->calculateFine(
            $issuedBooks,
            $fineSettings
        );
        $data["iTotalRecords"] = $tcount;
        $data["iTotalDisplayRecords"] = $tfcount;
        echo json_encode($data);
    }

    /**
     * Loads Request books
     *
     * @return void
     */
    public function loadRequestBook()
    {
        $start = $this->input->get("iDisplayStart", '0');
        $limit = $this->input->get("iDisplayLength", '10');
        $sortby = $this->input->get("iSortCol_0", '0');
        $sortDir = $this->input->get("sSortDir_0", 'ASC');
        $searchKey = $this->input->get("sSearch");
        $tcount = $tfcount = '';
        if ($sortby == 0) {
            $sortby = "RequestedAt";
            $sortDir = "DESC";
        }
        $data['aaData'] = $this->model->getRequestBooks(
            $start,
            $limit,
            $sortby,
            $sortDir,
            $searchKey,
            $tcount,
            $tfcount
        );
        $data["iTotalRecords"] = $tcount;
        $data["iTotalDisplayRecords"] = $tfcount;
        echo json_encode($data);
    }
}
