<?php
/**
 * Footer
 * php version 7.3.5
 *
 * @category View
 * @package  View
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */
defined('VALID_REQ') or exit('Invalid request');
if (!isset($footer)) {
    return;
} 
?>
<!-- footer starts -->
<footer id="footer">
    <div class="container">
        <div class="row">
            <div class="cols col-4">
                <div class="text-container">
                    <h4>About us</h4>
                    <p class="indent-para"><?php echo $footer->aboutUs;?>
                    </p>
                </div>
            </div>
            <div class="cols col-4">
                <div class="text-container">
                    <h4>Contact us</h4>
                    <p><i class="fa fa-map-marker symbols" aria-hidden="true"></i>&nbsp;&nbsp;<?php echo $footer->address;?><br>
                        <i class="fa fa-phone symbols" aria-hidden="true"></i>&nbsp;&nbsp;<?php echo $footer->mobile; ?><br>
                        <i class="fa fa-envelope-o symbols" aria-hidden="true"></i>&nbsp;&nbsp;<?php echo $footer->email; ?><br>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="container align-container-center">
        <div class="row">
            <div class="cols col-9">
                <p>Copyright © 2020 LMS</p>
            </div>
        </div>
    </div>
</footer>
<!-- end of footer -->
<!-- loading scripts -->
<script src="/static/js/core.js"></script>
<script src="/static/js/scriptHome.js"></script>
</body>

</html>