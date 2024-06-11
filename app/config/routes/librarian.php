<?php
/**
 * Routes File, all the route configurations of the librarian are defined here
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
use System\Core\InputData;
use System\Core\Utility;

Router::add('/librarian', 'admin/login');
Router::add('/librarian/login', 'admin/login');

Router::add(
    '/librarian/home',
    'admin/getHomePage',
    'get',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && $input->session('type') == LIBR_USER
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);

Router::add(
    '/librarian-profile',
    'admin/getProfile',
    'get',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && $input->session('type') == LIBR_USER
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);

Router::add(
    '/librarian-profile',
    'admin/updateProfile',
    'post',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && $input->session('type') == LIBR_USER
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);


Router::add(
    '/librarian/user-management',
    'userManage/manageRegUsers',
    'get',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && $input->session('type') == LIBR_USER
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);

Router::add(
    '/librarian/logout',
    'admin/logout',
    'get',
    function () {
        $input = new InputData();
        if ($input->session('login') == VALID_LOGIN
            && $input->session('type') == LIBR_USER
        ) {
            return true;
        } else {
            Utility::redirectURL('admin/login');
        }
    }
);
