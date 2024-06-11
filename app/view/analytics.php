<?php
/**
 * Analytics
 * php version 7.3.5
 *
 * @category View
 * @package  View
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */
use System\Helper\ChartHelper;
use System\Helper\PaginationHelper;
defined('VALID_REQ') or exit('Invalid request');
?>
<article class="main">
    <section>
        <div class="container div-card">
            <div class="row">
                <div class="cols col-9">
                    <h1>Analytics </h1>
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class='cols col-1'>
                    <label> From </label>
                    <input type='date' value="<?php echo $sDate; ?>" onchange="changeReport('/analytics');" id='sDate' class='form-control'>
                </div>
                <div class='cols col-1'>
                    <label>To </label>
                    <input type='date' value="<?php echo $eDate; ?>" onchange="changeReport('/analytics');" id='eDate' class='form-control'>
                </div>
                <div class='cols col-1'>
                    <label> Reports on </label>
                    <select onchange="changeReport('/analytics');" id='list' class='form-control select-input'>
                        <option value='book'> Top Books </option>
                        <option value='author'> Top Authors </option>
                        <option value='category'> Top Categories </option>
                        <option value='user'> Top Users </option>
                    </select>
                </div>
                <div class="form-buttons">
                        <a target='_blank' href="/report/book/csv?list=<?php echo $list; ?>&sDate=<?php echo $sDate; ?>&eDate=<?php echo $eDate ?>"><img class='icons' src="/static/img/symbols/excel.png"></a>
                        <a target='_blank' href="/report/book/pdf?list=<?php echo $list; ?>&sDate=<?php echo $sDate; ?>&eDate=<?php echo $eDate ?>"><img class='icons' src="/static/img/symbols/pdf.png"></a>
                </div>
            </div>
            <div class="row">
                <div class="cols" style='text-align-last:center;'>
                    <h3></h3>
                </div>
            </div>
            <div style="overflow-x:auto;">
                <?php 
                $values = [];
                foreach ($data as $temp) {
                    $values[] = [$temp->name, $temp->impression];
                }
                $properties = [
                    "xAxis"=> "Book Name",
                    "yAxis"=> "Impressions",
                    "maxVal"=> 100,
                    "color"=> "#005A9C",
                    "yDots" => 10
                ];
                echo ChartHelper::createChart("chart", 400, 850, 'responsiveChart');
                // echo getChartJs(); 
                echo ChartHelper::generateChart("chart", $values, $properties);
                ?>
            </div>
                    <!-- </div>
                </div> -->
            </div>
            <div class="container div-card">
            <div class="cols">
                <div class="div-card-body">
                    <div style="overflow-x:auto;">
                        <table id="report-list" class="tab_design">
                            <thead>
                                <tr>
                                    <th data-orderable="false">Sl. No</th>
                                    <?php 
                                    if ($list== "book") {
                                        echo PaginationHelper::generateTh(['Book Name', 'ISBN Number', 'Categories', 'Authors']);
                                    } elseif ($list== "user") {
                                        echo PaginationHelper::generateTh(['User Name', 'Full Name']);
                                    } elseif ($list== "category") {
                                        echo PaginationHelper::generateTh(['Category']);
                                    } elseif ($list== "author") {
                                        echo PaginationHelper::generateTh(['Author']);
                                    }
                                    ?>
                                    
                                    <th data-orderable="false">Impression%</th>
                                </tr>
                            </thead>
                            <tbody class="table_body">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</article>

<script>
    document.getElementById("list").value = "<?php echo $list?>";
    if ("<?php echo $list?>" == "book") {
        column = [{
            "render": function (data, type, row, meta) {
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
                "data": "categories"
            },
            {
                "data": "authors"
            },
            {
                "data": "impression"
            }
        ];
    } else if ("<?php echo $list?>" == "user") {
        column = [{
            "render": function (data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
            }
            },
            {
                "data": "name"
            },
            {
                "data": "fullName"
            },
            {
                "data": "impression",
                "render": function (item) {
                    return item + "%";
                }
            }
        ];
    } else {
        column = [{
            "render": function (data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
            }
            },
            {
                "data": "name"
            },
            {
                "data": "impression",
                "render": function (item) {
                    return item + "%";
                }
            }
        ];
    }
    $(document).ready(function() {
        url = "/<?php echo $list; ?>?sDate=<?php echo $sDate; ?>&eDate=<?php echo $eDate ?>";
        loadTableData("report-list", "/analytics/topList"+url, column);
    });
    document.getElementById('analytics').className += " active";
</script>