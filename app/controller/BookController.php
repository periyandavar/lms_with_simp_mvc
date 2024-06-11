<?php
/**
 * BookController File Doc Comment
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
use App\Model\BookModel;
use System\Core\Utility;
use System\Library\FormDataValidation;
use System\Library\Fields;
use App\Service\BookService;

/**
 * BookController Class Handles the requests related to the Books
 *
 * @category   Controller
 * @package    Controller
 * @subpackage BookController
 * @author     Periyandavar <periyandavar@gmail.com>
 * @license    http://license.com license
 * @link       http://url.com
 */
class BookController extends BaseController
{
    /**
     * Instantiate a new BookController instance.
     */
    public function __construct()
    {
        parent::__construct(new BookModel(), new BookService());
    }

    /**
     * Display all books with CRUD options
     *
     * @return void
     */
    public function manageBooks()
    {
        $user = $this->input->session('type');
        $this->loadLayout($user . "Header.html");
        $this->loadView("manageBooks");
        $this->loadLayout($user . "Footer.html");
        if ($this->input->session('msg') != null) {
            $this->addScript($this->input->session('msg'));
            Utility::setSessionData('msg', null);
        }
    }

    /**
     * Adds a new book
     *
     * @return void
     */
    public function newBook()
    {
        $user = $this->input->session('type');
        $this->loadLayout($user . "Header.html");
        $this->loadView("newBook");
        $this->loadLayout($user . "Footer.html");
    }

    /**
     * Display book details to edit them
     *
     * @param int $id BookID
     *
     * @return void
     */
    public function getToEdit(int $id)
    {
        if (!$data['book'] = $this->model->get($id)) {
            $script = "toast('Invalid Request..!');";
            Utility::setSessionData('msg', $script);
            $this->redirect("book-management");
        }
        $user = $this->input->session('type');
        $this->loadLayout($user . "Header.html");
        $this->loadView("newBook", $data);
        $this->loadLayout($user . "Footer.html");
    }

    /**
     * Displays the book details of the given Id in JSON
     *
     * @param int $id book Id
     *
     * @return void
     */
    public function get(int $id)
    {
        $result = $this->model->get($id);
        echo json_encode($result);
    }

    /**
     * Handles the search form and return the search result
     *
     * @return void
     */
    public function search()
    {
        $user = $this->input->session('type');
        $keyword = $this->input->get('search') ?? '';
        $offset = $this->input->get('offset') ?? 0;
        $limit = $this->input->get('limit') ?? 12;
        $data['books'] = $this->model->searchBook($keyword, $offset, $limit);
        $data['searchKey'] = $keyword;
        $this->loadLayout($user.'header.html');
        $this->loadView("searchBook", $data);
        $this->loadLayout($user.'footer.html');
        $this->includeScript('bookElement.js');
    }


    /**
     * Displays the books requested by the user
     *
     * @return void
     */
    public function loadBooks()
    {
        $offset = $this->input->get('offset') ?? 0;
        $limit = $this->input->get('limit') ?? 12;
        $data["books"] = ($search = $this->input->get("search"))
            ? $this->model->searchBook($search, $offset, $limit)
            : $this->model->getAvailableBooks(
                $offset,
                $limit
            );
        echo json_encode($data);
    }

    /**
     * Add a new book
     *
     * @return void
     */
    public function add()
    {
        $fdv = new FormDataValidation();
        $user = $this->input->session('type');
        $adminId = $this->input->session('id');
        $inputFields = [
            'name',
            'location',
            'author',
            'category',
            'publication',
            'isbn',
            'price',
            'stack',
            'description'
        ];
        $fields = new Fields($inputFields);
        $rules = [
            'author' => 'expressValidation /^[1-9]{1}[0-9,]*$/',
            'category' => 'expressValidation /^[1-9]{1}[0-9,]*$/',
            'isbn' => 'isbnValidation',
            'price' => 'positiveNumberValidation',
            'stack' => 'positiveNumberValidation'
        ];
        $fields->addRule($rules);
        $fields->setRequiredFields(...$inputFields);
        $fields->addValues($this->input->post());
        $flag = $fdv->validate($fields, $field);
        if ($flag) {
            $book = $fields->getValues();
            $uploadfile = $this->input->files('coverPic');
            $coverPic = uniqid() . "." . pathinfo(
                $uploadfile['name'],
                PATHINFO_EXTENSION
            );
            if ($fields->uploadFile($uploadfile, $coverPic, 'book')) {
                $book['coverPic'] = $coverPic;
                if ($this->model->addBook($book)) {
                    $this->log->activity(
                        "Admin user added new book with values "
                        . json_encode($book) . ", admin id: '$adminId'"
                    );
                    $script = "toast('New book added successfully..!', 'success');";
                    Utility::setSessionData('msg', $script);
                    $this->redirect('book-management');
                } else {
                    $script = "toast('Unable to add new book..!', 'danger',"
                        ." 'Failed');";
                }
            } else {
                $script = "toast('Error occured in file uploading";
                $script .= "and book not added..!', 'danger', 'Failed');";
            }
        } else {
            $script = "toast('Invalid $field..!', 'danger', 'Invalid Input');";
        }
        $formData = (object)$this->input->post();
        $formData->authorCodes = trim($this->input->post('author'), ",");
        $formData->categoryCodes = trim($this->input->post('category'), ",");
        $formData->authors = $this->model->getAuthorList($formData->authorCodes);
        $formData->categories = $this->model->getCatList($formData->categoryCodes);
        $data['book'] = $formData;
        $this->loadLayout($user . "Header.html");
        $this->loadView("newBook", $data);
        $this->loadLayout($user . "Footer.html");
        $this->includeScript("populate.js");
        $this->addScript($script);
    }

    /**
     * Checks whether the isbn is available or not
     *
     * @param string $isbn ISBN
     *
     * @return void
     */
    public function isAvailable(string $isbn): void
    {
        if (!(new FormDataValidation())->isbnValidation($isbn)) {
            echo json_encode(["result" => false]);
        } else {
            $id = $this->input->get('id', 0);
            $result = $this->model->isIsbnAvailable($isbn, $id);
            echo json_encode(["result" => $result]);
        }
    }

    /**
     * Loads Books
     *
     * @return void
     */
    public function load()
    {
        $start = $this->input->get("iDisplayStart", 0);
        $limit = $this->input->get("iDisplayLength", 10);
        $sortby = $this->input->get("iSortCol_0", '0');
        $sortDir = $this->input->get("sSortDir_0", 'ASC');
        $searchKey = $this->input->get("sSearch", '');
        $data['aaData'] = $this->model->getBooks(
            $start,
            $limit,
            $sortby+1,
            $sortDir,
            $searchKey,
            $tcount,
            $tfcount
        );
        $data["iTotalRecords"] = $tcount;
        $data["iTotalDisplayRecords"] = $tfcount;
        echo json_encode($data);
    }

    /**
     * Update the status of the book for the book Id $id to status $status
     *
     * @param int $id BookID
     *
     * @return void
     */
    public function changeStatus(int $id)
    {
        $data = $this->input->data();
        $adminId = $this->input->session('id');
        if ($result['result'] = $this->model->updateBook($data, $id)) {
            $this->log->activity(
                "Admin user updated the book($id) with new values "
                . json_encode($data) . ", admin id: '$adminId'"
            );
        }
        echo json_encode($result);
    }

    /**
     * Update the details of the book
     *
     * @param int $id BookID
     *
     * @return void
     */
    public function update(int $id)
    {
        global $config;
        $fdv = new FormDataValidation();
        $user = $this->input->session('type');
        $adminId = $this->input->session('id');
        $inputFields = [
            'name',
            'location',
            'author',
            'category',
            'publication',
            'isbn',
            'stack',
            'description',
            'price'
        ];
        $fields = new Fields($inputFields);
        $rules = [
            'author' => 'expressValidation /^[1-9]{1}[0-9,]*$/',
            'category' => 'expressValidation /^[1-9]{1}[0-9,]*$/',
            'isbn' => 'isbnValidation',
            'price' => 'positiveNumberValidation',
            'stack' => 'positiveNumberValidation'
        ];
        $fields->addRule($rules);
        $fields->setRequiredFields(...$inputFields);
        $fields->addValues($this->input->post());
        $flag = $fdv->validate($fields, $field);
        if ($flag) {
            $book = $fields->getValues();
            $uploadfile = $this->input->files('coverPic');
            if ($uploadfile['error'] == 0) {
                $coverPic = uniqid() . '.' . pathinfo(
                    $uploadfile['name'],
                    PATHINFO_EXTENSION
                );
                $flag = $fields->uploadFile($uploadfile, $coverPic, 'book');
                $book['coverPic'] = $coverPic;
            }
            if ($flag) {
                $oldPic = $this->model->getCoverPic($id);
                if ($this->model->update($book, $id)) {
                    $script = "toast('Book updated successfully..!', 'success');";
                    if (isset($book['coverPic'])) {
                        unlink(
                            $config['upload']
                            . '/'
                            . COVER_PIC_PATH
                            . $oldPic
                        );
                    }
                    $this->log->activity(
                        "Admin user updated the book($id) with new values "
                        . json_encode($book) . ", admin id: '$adminId'"
                    );
                    Utility::setSessionData('msg', $script);
                    $this->redirect('book-management');
                } else {
                    $script = "toast('Unable to update the book..!', 'danger', "
                        . "'Failed..!');";
                }
            } else {
                $script = "toast('Error in file uploading and book not added..!',";
                $script .= "'danger', 'Failed');";
            }
        } else {
            $script = "toast('Invalid $field..!', 'danger', 'Invalid Input');";
        }
        $formData = (object)$this->input->post();
        $formData->authorCodes = trim($this->input->post('author'), ",");
        $formData->categoryCodes = trim($this->input->post('category'), ",");
        $formData->authors = $this->model->getAuthorList($formData->authorCodes);
        $formData->categories = $this->model->getCatList($formData->categoryCodes);
        $formData->coverPic = $this->model->getCoverPic($id);
        $formData->id = $id;
        $data['book'] = $formData;
        $this->loadLayout($user . "Header.html");
        $this->loadView("newBook", $data);
        $this->loadLayout($user . "Footer.html");
        $this->addScript($script);
    }

    /**
     * Display the details of the single book
     *
     * @param int $id BookID
     *
     * @return void
     */
    public function view(int $id)
    {
        $user = $this->input->session('type');
        $data['book'] = $this->model->getBookDetails($id);
        $data['user'] = $user;
        if ($user != 'User') {
            $issuedData = $this->model->getIssuedUsers($id);
            $data['issuedUsers'] = $this->service->seperateUsers($issuedData);
        }
        $template = ($user == null) ? 'book' : 'bookdetail';
        $this->loadLayout($user . 'header.html');
        $this->loadView($template, $data);
        $this->loadLayout($user . 'footer.html');
    }

    /**
     * Displays all the available books to the user
     *
     * @return void
     */
    public function getAvailableBooks()
    {
        $this->loadLayout("userHeader.html");
        $data['books'] = $this->model->getAvailableBooks();
        $this->loadView("availablebooks", $data);
        $this->loadLayout("userFooter.html");
        $this->includeScript('bookElement.js');
    }

    /**
     * Delete the book
     *
     * @param int $id BookId
     *
     * @return void
     */
    public function delete(int $id)
    {
        $adminId = $this->input->session('id');
        if ($result['result'] = $this->model->delete($id, $msg)) {
            $this->log->activity(
                "Admin user deleted the book($id), admin id: '$adminId'"
            );
        }
        $result['msg'] = $msg;
        echo json_encode($result);
    }

    /**
     * Search for a book with given isbn
     *
     * @param string $isbn Search keys as string
     *
     * @return void
     */
    public function searchByIsbn(string $isbn)
    {
        $result['result'] = $this->model->getByIsbn($isbn);
        echo json_encode($result);
    }
}
