<?php
/**
 * Librarian Home
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
                    <a href="/librarian-profile" id="profile"><i class="fa fa-user-circle"
                            aria-hidden="true"></i><br>Profile</a>
                </div>
                <div class="cols col-1 div-card dashboard-icons">
                    <a href="/category-management" id="categories"><i class="fa fa-list-alt"
                            aria-hidden="true"></i><br>Categories</a>
                </div>
                <div class="cols col-1 div-card dashboard-icons">
                    <a href="/author-management" id="authors"><i class="fa fa-user-o" aria-hidden="true"></i><br>Authors</a>
                </div>
                <div class="cols col-1 div-card dashboard-icons">
                    <a href="/book-management" id="books"><i class="fa fa-book" aria-hidden="true"></i><br>Books</a>
                </div>
                <div class="cols col-1 div-card dashboard-icons">
                    <a href="/issued-book-management" id="issued"><i class="fa fa-table" aria-hidden="true"></i><br>Issued Books</a>
                </div>
                <div class="cols col-1 div-card dashboard-icons">
                    <a href="/request-management" id="request"><i class="fa fa-list" aria-hidden="true"></i><br>User
                        Request</a>
                </div>
                <div class="cols col-1 div-card dashboard-icons">
                    <a href="/librarian/user-management" id="request"><i class="fa fa-users"
                            aria-hidden="true"></i><br>Users</a>
                </div>
            </div>
        </div>
    </section>
</article>
<script>
    document.getElementById('home').className += " active";
</script>