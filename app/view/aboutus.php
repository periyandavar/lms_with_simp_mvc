<?php
/**
 * About us
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
<header class="about-container">
    <div class="header-content">
        <div class="container">
            <div class="row">
                <div class="cols">
                    <div class="text-container">
                        <h1>JOIN NOW TO <span class="morph"> </span></h1><br>
                        <p class="heading-para">
                            <?php
                            if (isset($aboutUs)) {
                                echo $aboutUs;
                            } ?>
                        </p>
                        <a class="btn-link" href="/signup">JOIN NOW</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- header ends -->
<script>
    document.getElementById("menu-aboutus").className += " active";
</script>