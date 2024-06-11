<?php
/**
 * Forgot password
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
                                echo '<a class="btn-link" href="/">GO TO HOME</a>';
                            else: ?>
                            <form action="" onsubmit="usernameValidator(event);" method="POST">
                                <div class="form-input-div">
                                    <label style="float:left;">Enter User Name <span class="required-star">*</span></label>
                                    <input class="form-control" type="text" pattern="^[a-zA-Z0-9_]+$" id="username"
                                        name="username" autocomplete="off" placeholder="User Name..." required="">
                                    <span id="user-availability-status" style="font-size:12px;"></span>
                                </div>
                                <button type='submit' class="btn-link">RECOVER</button>
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
