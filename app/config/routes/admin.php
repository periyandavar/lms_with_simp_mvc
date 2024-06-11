<?php
/**
 * Routes File, all the route configurations for admin are defined here
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

Router::add('/admin/login', 'admin/login');
Router::add('/admin', 'admin/login');
Router::add('/admin/login', 'admin/dologin', 'post');
Router::add('/admin/forgot-password', 'admin/forgotPassword');
Router::add('/admin/forgot-password', 'admin/recoveryRequest', 'post');

Router::add(
    '/admin-profile',
    'admin/getProfile',
    'get',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && $input->session('type') == ADMIN_USER
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);

Router::add(
    '/analytics/(book|category|author|user)',
    'report/analytics',
    'get',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && $input->session('type') == ADMIN_USER
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);

Router::add(
    '/analytics/topList/(book|category|author|user)',
    'report/topList',
    'get',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && $input->session('type') == ADMIN_USER
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);

Router::add(
    '/admin-profile',
    'admin/updateProfile',
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
    '/admin/home',
    '/admin/getHomePage',
    'get',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && $input->session('type') == ADMIN_USER
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);


Router::add(
    '/user/id/([a-zA-Z0-9_]+)',
    'issuedbook/getUserDetails',
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
    '/book/id/([1-9]{1}[0-9]*)',
    'book/get',
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
    '/admin/user-management',
    'userManage/manageAllUsers',
    'get',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && $input->session('type') == ADMIN_USER
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);

Router::add(
    '/admin/user-management',
    'userManage/addUser',
    'post',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && $input->session('type') == ADMIN_USER
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);

Router::add(
    '/admin/settings',
    'admin/getSettings',
    'get',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && $input->session('type') == ADMIN_USER
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);

Router::add(
    '/admin/settings',
    'admin/updateSettings',
    'post',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && $input->session('type') == ADMIN_USER
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);


Router::add(
    '/admin/cms',
    'admin/getCms',
    'get',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && $input->session('type') == ADMIN_USER
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);

Router::add(
    '/admin/cms',
    '/admin/updateCms',
    'post',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && $input->session('type') == ADMIN_USER
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);

Router::add(
    '/admin/logout',
    null,
    'get',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && $input->session('type') == ADMIN_USER
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);


Router::add(
    '/report/(book|category|author|user)/csv',
    'report/exportToCsv',
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
    '/report/(book|category|author|user)/pdf',
    'report/exportToPdf',
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
    '/category-management',
    'category/manage',
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
    '/author-management',
    'author/manage',
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
    '/issued-book-management',
    'Issuedbook/issue',
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
    '/request-management',
    'Issuedbook/manageUserRequest',
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
    '/book-management/books/isbn/([0-9X]{10})',
    'book/isAvailable',
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
    '/user-management/admin-users/email/([\s\S]*)',
    'admin/isEmailAvailable',
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
    '/book-management',
    'book/manageBooks',
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


Router::add('/authors/([A-Za-z ]+)/([0-9 ,]*)', 'author/search');
Router::add('/categories/([A-Za-z ]+)/([0-9 ,]*)', 'category/search');
