<?php
/**
 * CategoryController File Doc Comment
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
use App\Model\CategoryModel;
use System\Core\Utility;
use System\Library\FormDataValidation;
use System\Library\Fields;

/**
 * CategoryController Class Handles the request related to the categories
 *
 * @category   Controller
 * @package    Controller
 * @subpackage CategoryController
 * @author     Periyandavar <periyandavar@gmail.com>
 * @license    http://license.com license
 * @link       http://url.com
 */

class CategoryController extends BaseController
{
    /**
     * Instantiate a new CategoryController instance.
     */
    public function __construct()
    {
        parent::__construct(new CategoryModel());
    }

    /**
     * Get and display all the available categories
     *
     * @return void
     */
    public function manage()
    {
        $user = $this->input->session('type');
        $this->loadLayout($user."Header.html");
        $this->loadView("manageCategories");
        $this->loadLayout($user."Footer.html");
        if ($this->input->session('msg') != null) {
            $this->addScript($this->input->session('msg'));
            Utility::setSessionData('msg', null);
        }
    }

    /**
     * Loads categories
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
     * Delete the category and displays the result in JSON
     *
     * @param int $id CategoryId
     *
     * @return void
     */
    public function delete(int $id)
    {
        $adminId = $this->input->session('id');
        if ($result['result'] = $this->model->delete($id)) {
            $this->log->activity(
                "Admin user deleted the category($id), admin id: '$adminId'"
            );
        }
        echo json_encode($result);
    }

    /**
     * Displays the details of the given categoryId in JSON
     *
     * @param int $id CategoryId
     *
     * @return void
     */
    public function get(int $id)
    {
        $result['data'] = $this->model->get($id);
        echo json_encode($result);
    }

    /**
     * Change the status of the category
     *
     * @param int $id CategoryID
     *
     * @return void
     */
    public function changeStatus(int $id)
    {
        $adminId = $this->input->session('id');
        $data = $this->input->data();
        if ($result['result'] = $this->model->update($data, $id)) {
            $this->log->activity(
                "Admin user updated the category($id) with new values "
                . json_encode($data) . ", admin id: '$adminId'"
            );
        }
        echo json_encode($result);
    }

    /**
     * Update the details of the category
     *
     * @return void
     */
    public function update()
    {
        $fdv = new FormDataValidation();
        $adminId = $this->input->session('id');
        $user = $this->input->session('type');
        $fields = new Fields(['name']);
        $rules = [
            'name' => ['alphaSpaceValidation', 'required']
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
            $script = "toast('Category is updated successfully..!', 'success');";
            $this->log->activity(
                "Admin user updated the category(" . $this->input->post('id')
                . ") with new values " . json_encode($fields->getValues())
                . ", admin id: '$adminId'"
            );
        }
        Utility::setSessionData('msg', $script);
        $this->redirect('category-management');
    }

    /**
     * Add a new Category
     *
     * @return void
     */
    public function add()
    {
        $fdv = new FormDataValidation();
        $user = $this->input->session('type');
        $adminId = $this->input->session('id');
        $fields = new Fields(['name']);
        $fields->setRequiredFields('name');
        $rules = [
            'name' => 'alphaSpaceValidation',
        ];
        $fields->addRule($rules);
        $fields->addValues($this->input->post());
        if (!$fdv->validate($fields, $field)) {
            $script = "toast('Invalid $field..!', 'danger', 'Invalid Input');";
        } elseif (!$this->model->add($fields->getValues())) {
            $script = "toast('Unable to add new category..!', 'danger',"
                . " 'Failed');";
        } else {
            $script = "toast('New category is added successfully..!', 'success');";
            $this->log->activity(
                "Admin user added new category with values "
                . json_encode($fields->getValues()) . ", admin id: '$adminId'"
            );
        }
        Utility::setSessionData('msg', $script);
        $this->redirect('category-management');
    }

    /**
     * Search for category with given search keys and displays the results in JSON
     *
     * @param string $searchKey  Search keys
     * @param string $ignoreList Category Id list with , as seperator
     *                           which will be ignored during search
     *
     * @return void
     */
    public function search(string $searchKey, string $ignoreList)
    {
        $result['result'] = $this->model->getCategoriesLike($searchKey, $ignoreList);
        echo json_encode($result);
    }
}
