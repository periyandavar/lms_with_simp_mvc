<?php
/**
 * Manage books
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
                    <h1>Books &nbsp;<a class="btn-link" href="/book-management/new-book">Add</a></h1>
                    <hr>
                </div>
            </div>
            <div class="div-card-body">
                <div style="overflow-x:auto;">
                    <table id="book-list" class="tab_design">
                        <thead>
                            <tr>
                                <th data-orderable="false">Sl. No</th>
                                <th>Book</th>
                                <th>ISBN</th>
                                <th>location</th>
                                <th>Created at</th>
                                <th>Updated at</th>
                                <th>Status</th>
                                <th class="notexport" data-orderable="false">Action</th>
                            </tr>
                        </thead>
                        <tbody class="table_body">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</article>

<script>
    document.getElementById('books').className += " active";
    column = [{
            "render": function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
        {
            "data": "name"
        },
        {
            "data": "isbn"
        },
        {
            "data": "location"
        },
        {
            "data": "createdAt"
        },
        {
            "data": "updatedAt"
        },
        {
            "data": null,
            "render": function(item) {
                code = '<div class="checkbox"><input type="checkbox" ';
                code += 'onchange="changeStatus(event,'
                code += "'/book-management/books/" + item.id + "');" + '" ';
                code += item.status == 1 ? "checked" : '';
                code += '></div>';
                return code;
            }
        },
        {
            "data": null,
            "render": function(item) {
                code = '<a href=';
                code += "'/book-management/books/" + item.id + "'";
                code +=
                    'class="button-control icon-btn positive" title="edit"><i class="fa fa-edit"></i></a> <button type="button"';
                code += ' onclick="deleteItem(' + "'/book-management/books/" + item.id + "', 'book');" + '"';
                code +=
                    'class="button-control icon-btn negative" title="delete"><i class="fa fa-trash"></i></button>';
                return code;
            }
        }
    ]
    $(document).ready(function() {
        loadTableData("book-list", "/book-management/books", column);
    });
</script>