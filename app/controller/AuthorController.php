<?php
/**
 * AuthorController File Doc Comment
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
use App\Model\AuthorModel;
use System\Core\Utility;
use System\Library\FormDataValidation;
use System\Library\Fields;

/**
 * AuthorController Class Handles the requests related to the authors
 *
 * @category   Controller
 * @package    Controller
 * @subpackage AuthorController
 * @author     Periyandavar <periyandavar@gmail.com>
 * @license    http://license.com license
 * @link       http://url.com
 */
class AuthorController extends BaseController
{
    /**
     * Instantiate a new AuthorController instance.
     */
    public function __construct()
    {
        parent::__construct(new AuthorModel());
    }

    /**
     * Displays all authors request
     *
     * @return void
     */
    public function manage()
    {
        $user = $this->input->session('type');
        $this->loadLayout($user."Header.html");
        $this->loadView("manageAuthors");
        $this->loadLayout($user."Footer.html");
        if ($this->input->session('msg') != null) {
            $this->addScript($this->input->session('msg'));
            Utility::setSessionData('msg', null);
        }
    }

    /**
     * Loads Authors
     *
     * @return void
     */
    public function load()
    {
        $start = $this->input->get("iDisplayStart", '0');
        $limit = $this->input->get("iDisplayLength", '10');
        $sortby = $this->input->get("iSortCol_0", '0');
        $sortDir = $this->input->get("sSortDir_0", 'ASC');
        $searchKey = $this->input->get("sSearch");
        $data['aaData'] = $this->model->getAll(
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
     * Add new author
     *
     * @return void
     */
    public function add()
    {
        $fdv = new FormDataValidation();
        $fields = new Fields(['name']);
        $user = $this->input->session('type');
        $adminId = $this->input->session('id');
        $fields->setRequiredFields('name');
        $rules = [
            'name' => 'alphaSpaceValidation',
        ];
        $fields->addRule($rules);
        $fields->addValues($this->input->post());
        if (!$fdv->validate($fields, $field)) {
            $script = "toast('Invalid $field..!', 'danger', 'Invalid Input');";
        } elseif (!$this->model->add($fields->getValues())) {
            $script = "toast('Unable to add new author..!', 'danger', 'Failed');";
        } else {
            $script = "toast('New author is added successfully..!', 'success');";
            $this->log->activity(
                "Admin user added new author with values "
                . json_encode($fields->getValues()) . ", admin id: '$adminId'"
            );
        }
        Utility::setSessionData('msg', $script);
        $this->redirect('author-management');
    }

    /**
     * Get the author details by id and display it in JSON format
     *
     * @param int $id AuthorID
     *
     * @return void
     */
    public function get(int $id)
    {
        $result['data'] = $this->model->get($id);
        echo json_encode($result);
    }

    /**
     * Change the status of the author & displays the success/failure message in JSON
     *
     * @param int $id AuthorID
     *
     * @return void
     */
    public function changeStatus(int $id)
    {
        $adminId = $this->input->session('id');
        $data = $this->input->data();
        $result['result'] = $this->model->update($data, $id);
        $this->log->activity(
            "Admin user updated the author($id) with new values "
            . json_encode($data) . ", admin id: '$adminId'"
        );
        echo json_encode($result);
    }

    /**
     * Update the author details
     *
     * @return void
     */
    public function update()
    {
        $fdv = new FormDataValidation();
        $user = $this->input->session('type');
        $adminId = $this->input->session('id');
        $fields = new Fields(['name']);
        $rules = [
            'name' => ['alphaSpaceValidation', 'required'],
        ];
        $fields->addRule($rules);
        $fields->addValues($this->input->post());
        if (!$fdv->validate($fields, $field)) {
            $script = "toast('Invalid $field..!', 'danger', 'Invalid Input');";
        } elseif (!$this->model->update(
            $fields->getValues(),
            $this->input->post('id')
        )
        ) {
            $script = "toast('Unable to update..!', 'danger', 'Failed');";
        } else {
            $script = "toast('Author is updated successfully..!', 'success');";
            $this->log->activity(
                "Admin user updated the author(" . $this->input->post('id')
                . ") with new values " . json_encode($fields->getValues())
                . ", admin id: '$adminId'"
            );
        }
        Utility::setSessionData('msg', $script);
        $this->redirect('author-management');
    }

    /**
     * Delete the existing author
     *
     * @param int $id AuthorID
     *
     * @return void
     */
    public function delete(int $id)
    {
        $adminId = $this->input->session('id');
        if ($result['result'] = $this->model->delete($id)) {
            $this->log->activity(
                "Admin user deleted the author($id), admin id: '$adminId'"
            );
        }
        echo json_encode($result);
    }

    /**
     * Search the author with given keys
     *
     * @param string $searchKey  Keys to search
     * @param string $ignoreList The list of authorcodes with , seperator
     *                           to ignore in search
     *
     * @return void
     */
    public function search(string $searchKey, string $ignoreList = '')
    {
        $result['result'] = $this->model->getAuthorsLike($searchKey, $ignoreList);
        echo json_encode($result);
    }
}
