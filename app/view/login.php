<?php
/**
 * Login
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
<!-- top container starts -->
<div class="top-container">
    <div class="header-content">
        <div class="container">
            <div class="row">
                <div class="cols">
                    <div class="text-container">
                        <h1>LOGIN NOW TO <span class="morph"> </span></h1><br>
                        <!-- <p class="heading-para">We store the energy that fuels the imagination. we open up windows to the world and inspire you to explore and achieve, and contribute to improving your quality of life.</p>
                        <a class="btn-link" href="home/registration">JOIN NOW</a> -->
                        <section class="form-card">
                            <h1>User Login</h1>
                            <hr>
                            <form action="/login" method="POST">
                                <div class="form-input-div">
                                    <label>Enter User Name <span class="required-star">*</span></label>
                                    <input class="form-control" type="text" pattern="^[a-zA-Z0-9_]+$" id="username"
                                        name="username" autocomplete="off" placeholder="User Name..." required="">
                                    <span id="user-availability-status" style="font-size:12px;"></span>
                                </div>
                                <div class="form-input-div">
                                    <label>Enter Password <span class="required-star">*</span></label>
                                    <input class="form-control" name="password" type="password" placeholder="********"
                                        autocomplete="off" required="">
                                    <meter id="pass1str" min="0" low="40" high="95" max="100" optimum="50"
                                        style="display:none" value="0"></meter>
                                    <span id="password-span" style="display:none"></span>
                                </div>
                                <div class="form-input-div">
                                    <label>Verification code <span class="required-star">*</span> </label>
                                    <input type="text" name="captcha" maxlength="5" autocomplete="off"
                                        placeholder="Verification Code..." required=""
                                        style="width: 150px; height: 25px;">&nbsp;<img id="logImg"
                                        src="<?php echo Utility::baseURL() . "/captcha"; ?>">
                                </div>
                                <div class="form-buttons">
                                    <button type="submit" name="login" class="btn-link">Login</button>
                                    <!-- <a href="registration" class="button-control negative">signup</a> -->
                                </div>
                                <div class="msg">
                                    <?php if (isset($msg)) {
                                        echo $msg;
                                    } ?>
                                </div>
                                <br>
                                <span> Forgot password..? <a class="link" href="/forgot-password"> Recover here
                                    </a></span>
                            </form>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- top container ends -->
<script>
    document.getElementById("menu-login").className += " active";
</script>
<script src="<?php echo Utility::baseURL();?>/static/js/form.js"></script>