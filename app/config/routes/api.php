<?php
/**
 * Routes File all the route route configurations for api defined here
 * php version 7.3.5
 *
 * @category   Route
 * @package    Routes
 * @subpackage Routes
 * @author     Periyandavar <periyandavar@gmail.com>
 * @license    http://license.com license
 * @link       http://url.com
 */
defined('VALID_REQ') or exit('Invalid request');
use System\Core\Router;
use System\Core\Utility;
use System\Core\InputData;

Router::add(
    '/category-management/categories',
    'category/load',
    'get',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && ($input->session('type') == LIBR_USER
            ||$input->session('type') == ADMIN_USER)
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);

Router::add(
    '/category-management/categories',
    null,
    'post',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && ($input->session('type') == LIBR_USER
            ||$input->session('type') == ADMIN_USER)
        ) {
            $action = $input->post('action');
            $action != 'add' && $action != 'update'
            ? Utility::redirectURL('category-management')
            : Router::dispatch("category/$action");
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);



Router::add(
    '/category-management/categories/([1-9]{1}[0-9]*)',
    'category/delete',
    'delete',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && ($input->session('type') == LIBR_USER
            ||$input->session('type') == ADMIN_USER)
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);

Router::add(
    '/category-management/categories/([1-9]{1}[0-9]*)',
    'category/get',
    'get',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && ($input->session('type') == LIBR_USER
            ||$input->session('type') == ADMIN_USER)
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);


Router::add(
    '/category-management/categories/([1-9]{1}[0-9]*)',
    'category/changeStatus',
    'put',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && ($input->session('type') == LIBR_USER
            ||$input->session('type') == ADMIN_USER)
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);

Router::add(
    '/author-management/authors',
    'author/load',
    'get',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && ($input->session('type') == LIBR_USER
            ||$input->session('type') == ADMIN_USER)
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);


Router::add(
    '/author-management/authors',
    null,
    'post',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && ($input->session('type') == LIBR_USER
            ||$input->session('type') == ADMIN_USER)
        ) {
            $action = $input->post('action');
            $action == null
            ? Utility::redirectURL('authors')
            : Router::dispatch("/author/$action");
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);

Router::add(
    '/author-management/authors/([1-9]{1}[0-9]*)',
    'author/get',
    'get',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && ($input->session('type') == LIBR_USER
            ||$input->session('type') == ADMIN_USER)
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);

Router::add(
    '/author-management/authors/([1-9]{1}[0-9]*)',
    'author/delete',
    'delete',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && ($input->session('type') == LIBR_USER
            ||$input->session('type') == ADMIN_USER)
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);

Router::add(
    '/author-management/authors/([1-9]{1}[0-9]*)',
    'author/changeStatus',
    'put',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && ($input->session('type') == LIBR_USER
            ||$input->session('type') == ADMIN_USER)
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);

Router::add(
    '/issued-book-management/issued-books',
    'issuedbook/loadIssuedBook',
    'get',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && ($input->session('type') == LIBR_USER
            || $input->session('type') == ADMIN_USER)
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);

Router::add(
    '/request-management/requests',
    'Issuedbook/loadRequestBook',
    'get',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && ($input->session('type') == LIBR_USER
            || $input->session('type') == ADMIN_USER)
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);

Router::add(
    '/request-management/requests/([1-9]{1}[0-9]*)',
    'Issuedbook/manageRequest',
    'get',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && ($input->session('type') == LIBR_USER
            || $input->session('type') == ADMIN_USER)
        ) {
            return true;
        } else {
            Utility::redirectURL('/login');
        }
    }
);

Router::add(
    '/request-management/requests/([1-9]{1}[0-9]*)',
    'Issuedbook/updateRequest',
    'post',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && ($input->session('type') == LIBR_USER
            || $input->session('type') == ADMIN_USER)
        ) {
            return true;
        } else {
            Utility::redirectURL('/login');
        }
    }
);


Router::add(
    '/issued-book-management/issued-books',
    'Issuedbook/add',
    'post',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && ($input->session('type') == LIBR_USER
            || $input->session('type') == ADMIN_USER)
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);

Router::add(
    '/issued-book-management/issued-books/([1-9]{1}[0-9]*)',
    'Issuedbook/markAsReturn',
    'put',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && ($input->session('type') == LIBR_USER
            || $input->session('type') == ADMIN_USER)
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);

Router::add(
    '/book-management/books',
    'book/load',
    'get',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && ($input->session('type') == LIBR_USER
            ||$input->session('type') == ADMIN_USER)
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);

Router::add(
    '/book-management/new-book',
    'book/newBook',
    'get',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && ($input->session('type') == LIBR_USER
            ||$input->session('type') == ADMIN_USER)
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);

Router::add(
    '/book-management/new-book',
    'book/add',
    'post',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && ($input->session('type') == LIBR_USER
            ||$input->session('type') == ADMIN_USER)
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);

Router::add(
    '/book-management/books/([1-9]{1}[0-9]*)',
    'book/getToEdit',
    'get',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && ($input->session('type') == LIBR_USER
            ||$input->session('type') == ADMIN_USER)
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);
Router::add(
    '/book-management/books/([1-9]{1}[0-9]*)',
    'book/update',
    'post',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && ($input->session('type') == LIBR_USER
            ||$input->session('type') == ADMIN_USER)
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);

Router::add(
    '/book-management/books/([1-9]{1}[0-9]*)',
    'book/delete',
    'delete',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && ($input->session('type') == LIBR_USER
            ||$input->session('type') == ADMIN_USER)
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);

Router::add(
    '/book-management/books/([1-9]{1}[0-9]*)',
    'book/changeStatus',
    'put',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && ($input->session('type') == LIBR_USER
            ||$input->session('type') == ADMIN_USER)
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);


Router::add(
    '/user-management/all-users',
    'userManage/loadAllUser',
    'get',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && ($input->session('type') == LIBR_USER
            ||$input->session('type') == ADMIN_USER)
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);

Router::add(
    '/user-management/all-users/(user|librarian|admin)/([1-9]{1}[0-9]*)',
    'userManage/delete',
    'delete',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && ($input->session('type') == ADMIN_USER)
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);

Router::add(
    '/user-management/reg-users',
    'userManage/loadRegUser',
    'get',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && ($input->session('type') == LIBR_USER
            ||$input->session('type') == ADMIN_USER)
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);


Router::add(
    '/user/user-name/([a-zA-Z0-9_]+)',
    'userManage/search',
    'get',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && ($input->session('type') == LIBR_USER
            ||$input->session('type') == ADMIN_USER)
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);

Router::add(
    '/book/isbn-number/([0-9]+)',
    'book/searchByIsbn',
    'get',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && ($input->session('type') == LIBR_USER
            ||$input->session('type') == ADMIN_USER)
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);

Router::add(
    '/user/roles',
    'userManage/getUserRoles',
    'get',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && $input->session('type') == ADMIN_USER
        ) {
            return true;
        } else {
            echo "Invalid Request";
        }
    }
);

Router::add(
    '/book/search/?([^/]+)?/?([0-9]+)?/?([0-9]+)?',
    'book/findMoreBooks',
    'get',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);

Router::add(
    '/user-request-management/user-request/([1-9]{1}[0-9]*)',
    'user/removeRequest',
    'delete',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && $input->session('type') == REG_USER
        ) {
            return true;
        } else {
            Utility::redirectURL('login');
        }
    }
);

Router::add(
    '/books/load',
    'book/loadBooks',
    'get'
);


Router::add(
    '/book/([1-9]{1}[0-9]*)',
    'book/view',
    'get'
);
