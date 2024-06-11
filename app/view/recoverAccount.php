<?php
/**
 * Recover Account
 * php version 7.3.5
 *
 * @category View
 * @package  View
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */
defined('VALID_REQ') or exit('Invalid request');
use System\Core\Utility;
?>
<!-- header starts -->
<header class="container">
    <div class="header-content">
        <div class="container">
            <div class="row">
                <div class="cols">
                    <div class="text-container">
                        <div class='msg-card container-card'>
                            <h1>Recover Account</h1><br>
                            <?php
                            if (isset($flag)):
                                ($flag)
                                    ? print "<p class='msg'>A recovery link is sent to your registered email account with" 
                                        ."use that link to recover your account..! link will be valid"
                                        ."for 10 minutes </p>"
                                    : print "<p class='msg'>Unable to send a recovery mail please try again later..!</p>";
                            else: ?>
                            <form action="" onsubmit="passwordValidator(event);" method="POST">
                                <div class="form-input-div">
                                    <label>Enter Password <span class="required-star">*</span></label>
                                    <input class="form-control" onkeyup="passStrength('password')" minlength="6"
                                        type="password" id="password" name="password" placeholder="********"
                                        autocomplete="off" required="">
                                    <meter id="pass1str" min="0" low="40" high="95" max="100" optimum="50"
                                        style="display:none" value="0"></meter>
                                    <span id="password-span" style="display:none"></span>
                                </div>
                                <div class="form-input-div">
                                    <label>Confirm Password <span class="required-star">*</span></label>
                                    <input class="form-control"
                                        onkeyup="checkConfirm('password','confirmPassword','confirmPassword-span')" minlength="6"
                                        type="password" id="confirmPassword" name="confirmpassword"
                                        placeholder="********" autocomplete="off" required="">
                                    <span id="confirmPassword-span" style="color:red"></span>
                                </div>
                                <button type='submit' class="btn-link">Change Password</button>
                            </form>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- header ends -->
<script src="<?php echo Utility::baseURL();?>/static/js/form.js"></script>
