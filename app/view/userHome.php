<?php
/**
 * User Home
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
        <div class="container">
            <div class="row">
                <div class="cols col-1 div-card dashboard-icons">
                    <a href="/user-profile">
                        <i class="fa fa-user-circle" aria-hidden="true"></i> <br>
                        Profile
                    </a>
                </div>
                <div class="cols col-1 div-card dashboard-icons">
                    <a href="/available-books">
                        <i class="fa fa-book" aria-hidden="true"></i> <br>
                        Available Books
                    </a>
                </div>
                <div class="cols col-1 div-card dashboard-icons">
                    <a href="/requested-books">
                        <i class="fa fa-bookmark" aria-hidden="true"></i> <br>
                        Requested for lend
                    </a>
                </div>
                <div class="cols col-1 div-card dashboard-icons">
                    <a href="/lent-books">
                        <i class="fa fa-book" aria-hidden="true"></i> <br>
                        Lent Books
                    </a>
                </div>
            </div>
        </div>
    </section>
</article>
<script>
    document.getElementById('home').className += " active";
</script>