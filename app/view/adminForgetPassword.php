<?php
/**
 * Admin Login
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
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Library</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="icon" type="image/png"
        href="/static/img/favicon.png" />
    <link rel="stylesheet" type="text/css"
        href="/static/css/form.css">
    <link rel="stylesheet" type="text/css"
        href="/static/css/core.css">
    <link rel="stylesheet" type="text/css"
        href="/static/css/toast.css">
    <link rel="stylesheet" type="text/css"
        href="/static/css/login.css">
    <link rel="stylesheet" type="text/css" href="/static/css/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/static/css/preloader.css">
</head>

<body>
    <div class="preloader" id="loader">
        <div class="loader l-0">
            <div class="loader l-1">
                <div class="loader l-2">
                    <div class="loader l-3">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="left-cover"></div>
    <div class="right-cover"></div>
    <article>

        <section>
            <div class="container">
                <div class="row">
                    <div class="cols col-2 login-wrapper">
                        <!-- <div class="login-wrapper"> -->
                        <div class="text-container">
                            <div class="logo-banar"
                                src="<?php echo Utility::baseURL()?>/static/img/favicon.png">
                            </div>
                            <h3>WELCOME</h3>
                            <p> Sign in with your login credentials here..</p><br>
                            <form action="" onsubmit="emailValidator(event);" method="POST">
                                <div class="form-input-div">
                                    <i class="fa fa-envelope" aria-hidden="true"></i>&nbsp;
                                    <input class="form-control" type="email" name="email" id="emailid"
                                        placeholder="Email ID" autocomplete="off" required="">
                                </div>
                                <div class="form-buttons">
                                    <button type="submit" class="btn-link">Recover</button>
                                </div>
                            </form>
                             
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </article>
    <script src="../static/js/form.js"></script>
    <script src="../static/js/core.js"></script>
</body>

</html>