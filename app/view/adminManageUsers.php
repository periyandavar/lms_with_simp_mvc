<?php
/**
 * AdminMangeUsers
 * php version 7.3.5
 *
 * @category View
 * @package  View
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */
defined('VALID_REQ') or exit('Invalid request');
?>
<article class="main">
    <section>
        <div class="container div-card">
            <div class="row">
                <div class="cols col-9">
                    <h1>Users &nbsp;<a class="btn-link" onclick="openModal('addRecord');loadRoles();">New User</a></h1>
                    <hr>
                </div>
            </div>
            <div class="div-card-body">
                <div style="overflow-x:auto;">
                    <table class="tab_design" id='user-list'>
                        <thead>
                            <tr>
                                <th data-orderable="false">#</th>
                                <th>Full Name</th>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>User Type</th>
                                <th>Registered At</th>
                                <th data-orderable="false">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal-shadow" id="addRecord">
            <div class="modal">
                <span class="close-modal" onclick="closeModal('addRecord');">✖</span>
                <h1>Add New User</h1>
                <hr><br>
                <form action="/admin/user-management" onsubmit="createUserFormValidator(event);" method="post">
                    <div class="form-input-div">
                        <label>Full Name <span class="required-star">*</span></label>
                        <input class="form-control" type="text" id="fullname" name="fullName" autocomplete="off"
                            placeholder="Full Name" required="">
                            <span id="fullname-span" class="span-err-msg"></span>
                    </div>
                    <div class="form-input-div">
                        <label>Email <span class="required-star">*</span></label>
                        <input class="form-control" onblur="checkEmail(event.target.value, 'email-span', true)" type="email" id="email" name="email" autocomplete="off"
                            placeholder="email" required="">
                            <span id="email-span" class="span-err-msg"></span>
                    </div>

                    <div class="form-input-div">
                        <label>Role <span class="required-star">*</span></label>
                        <select class="form-control" type="text" id="role" name="role" required="">
                            <option style="display:none" value=''>Select Role</option>
                        </select>
                        <span id="role-span" class="span-err-msg"></span>
                    </div>

                    <div class="form-input-div">
                        <label>Password <span class="required-star">*</span></label>
                        <input class="form-control" onkeyup="passStrength('password')" type="password" id="password"
                            name="password" required="" placeholder="********" autocomplete="off">
                        <meter id="pass1str" min="0" low="40" high="95" max="100" optimum="50" style="display:none"
                            value="0"></meter>
                        <span id="password-span" class="span-err-msg"></span>
                    </div>
                    <div class="form-input-div">
                        <label>Confirm Password <span class="required-star">*</span> </label>
                        <input class="form-control" onkeyup="checkConfirm('password','confirmPassword','confirmPassword-span')"
                            type="password" required="" id="confirmPassword" name="confirmpassword" placeholder="********"
                            autocomplete="off">
                        <span id="confirmPassword-span" class="span-err-msg"></span>
                    </div>
                    <div class="form-buttons">
                        <button type="submit" id="submit-btn" value='0' class="btn-link">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</article>

<script>
    document.getElementById('manageUsers').className += " active";
    column = [{
            "render": function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
        {
            "data": "fullName"
        },
        {
            "data": "userName"
        },
        {
            "data": "email"
        },
        {
            "data": "mobile"
        },
        {
            "data": "role"
        },
        {
            "data": "createdAt"
        },
        {
            "data": null,
            "render": function(item) {
                code = '<button type="button"';
                code += ' onclick="deleteItem(' + "'/user-management/all-users/" + item.role.toLowerCase() +"/" + item.id + "', 'user');" + '"';
                code +=
                    'class="button-control icon-btn negative" title="delete"><i class="fa fa-trash"></i></button>';
                return code;
            }
        }
    ]
    $(document).ready(function() {
        loadTableData("user-list", "/user-management/all-users", column);
    });
</script>