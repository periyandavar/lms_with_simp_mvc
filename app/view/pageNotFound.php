<?php
/**
 * 404 page
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
<!-- header starts -->
<header class="err-404-container">
    <div class="header-content">
        <div class="container">
            <div class="row">
                <div class="cols">
                    <div class="text-container">
                        <div class='msg-card'>
                            <h1>404 Page Not Found</h1><br>
                            <p class="heading-para"><?php echo $msg; ?></p>
                            <a class="btn-link" href="/">GO TO HOME</a>
                        </div>    
                    </div>
                </div> 
            </div> 
        </div> 
    </div> 
</header> 
<!-- header ends -->
