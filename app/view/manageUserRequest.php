<?php
/**
 * Manage user requests
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
                    <h1>User Request for Books
                        <hr>
                </div>
            </div>
            <div class="div-card-body">
                
                <div style="overflow-x:auto;">
                    <table class="tab_design" id='book-list'>
                        <thead>
                            <tr>
                                <th data-orderable="false">#</th>
                                <th>ISBN</th>
                                <th>Book Name</th>
                                <th>User Name</th>
                                <th>Requested Date</th>
                                <th>Comments</th>
                                <th>Status</th>
                                <th data-orderable="false">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </section>
</article>

<script>
    document.getElementById('request').className += " active";
    column = [{
            "render": function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
        {
            "data": "isbn"
        },
        {
            "data": "bookName"
        },
        {
            "data": "userName"
        },
        {
            "data": "requestedAt"
        },
        {
            "data": "comments"
        },
        {
            "data": "status"
        },
        {
            "data":null,
            "render": function(item) {   
                code = ' <a type="button" href="/request-management/requests/'+item.id+'"';
                code += ' class="button-control icon-btn positive" title="edit"><i';
                code += ' class="fa fa-edit"></i></a>';
                return code;
            }
        },
    ]
    $(document).ready(function() {
        loadTableData("book-list", "/request-management/requests", column);
    });
</script>