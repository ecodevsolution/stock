<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;


$this->registerJs('
        $(document).ready(function(){
            var myVar;
           
             $( "#home" ).click(function() {
                myFunction(this);
            });

            function myFunction(div) {
                $(".loader").toggle();
                $(div).toggle();

            }    
        });
    ');
     
       
     $root = '@web';
     $this->registerJsFile("https://code.jquery.com/ui/1.12.1/jquery-ui.js",
    ['depends' => [\yii\web\JqueryAsset::className()],
    'position' => View::POS_HEAD]);
    //   $this->registerJsFile($root."/components/plugins/arcseldon-jquery.sparkline/dist/jquery.sparkline.js",
    //  ['depends' => [\yii\web\JqueryAsset::className()],
    //  'position' => View::POS_END]);
    //  $this->registerJsFile($root."/components/js/global/sparkline_chart.js",
    //  ['depends' => [\yii\web\JqueryAsset::className()],
    //  'position' => View::POS_END]);
     $this->registerJsFile($root."/components/js/chart.js",
     ['depends' => [\yii\web\JqueryAsset::className()],
     'position' => View::POS_HEAD]);
    
    $connection = \Yii::$app->db;       


?>
<style>
    .loading-overlay{
        position: absolute;
        left: 0; 
        top: 0; 
        right: 0; 
        bottom: 0;
        z-index: 2;
        background: rgba(255,255,255,0.7);
    }
    .overlay-content {
        position: absolute;
        transform: translateY(-50%);
        -webkit-transform: translateY(-50%);
        -ms-transform: translateY(-50%);
        top: 50%;
        left: 0;
        right: 0;
        text-align: center;
        color: #555;
    }
</style>

 <script>
    $(function() {
        $("#datepicker1").datepicker({ dateFormat: 'yy-mm-dd' });
    });
    $(function() {
        $("#datepicker2").datepicker({ dateFormat: 'yy-mm-dd' });
    });

    function getUsers(tgl_awal,tgl_akhir,payment){
        $.ajax({
            type: 'POST',
            url: '?r=report/getdata',
            data: 'tgl_awal='+tgl_awal+'&tgl_akhir='+tgl_akhir+'&payment='+payment,
            beforeSend:function(html){
                $('.loading-overlay').show();
            },
            success:function(html){
                $('.loading-overlay').hide();
                $('#userData').html(html);
            }
        });
    }

  
</script>
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="dashboard_v4_box_block">
                <!--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTable">Primary</button>-->
                <a href="<?= Yii::$app->homeUrl; ?>"  id="home">
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="content">
                            <div class="dashboard_v4_box_icon float-xs-left primary_box">
                                <i class="fa fa-home"></i>
                            </div>
                            <div class="dashboard_v4_box_title float-xs-right">
                                <h4>Dashboard</h4>                                
                            </div>
                        </div>
                    </div>
                </a>
                <?php
                    include "menu.php";
                ?>
            </div>
        </div>
        <div class="row">
             <div class="content" >
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">

                        <div class="divider15 divider-lg-spacing"></div>
                        <div class="widget-primary alert-left-border-icon">
                            <div class="widget-content alert alert-primary m-0" role="alert">
                                <div class="alert-media-right" style="display: inline-block;" >
                                    <p>
                                        Date Range : <input type="text"  required id="datepicker1"> To <input type="text" required id="datepicker2">                                                   
                                        <input type="button" class="btn btn-primary" value="Search" onclick="getUsers($('#datepicker1').val(),$('#datepicker2').val(),$('#payment').val())"/>
                                    </p>

                                </div>
                                <div class="alert-media-right" style="float:right;">
                                    <p>
                                        <script>

                                            function downloadCSV(csv, filename) {
                                                var csvFile;
                                                var downloadLink;
                                                filename = 'OrderHistory <?= date('Y-m-d')?>.csv';
                                                // CSV file
                                                csvFile = new Blob([csv], {type: "text/csv"});

                                                // Download link
                                                downloadLink = document.createElement("a");

                                                // File name
                                                downloadLink.download = filename;

                                                // Create a link to the file
                                                downloadLink.href = window.URL.createObjectURL(csvFile);

                                                // Hide download link
                                                downloadLink.style.display = "none";

                                                // Add the link to DOM
                                                document.body.appendChild(downloadLink);

                                                // Click download link
                                                downloadLink.click();
                                            }

                                            function exportTableToCSV(filename) {
                                                var csv = [];
                                                var rows = document.querySelectorAll("table tr");
                                                
                                                for (var i = 0; i < rows.length; i++) {
                                                    var row = [], cols = rows[i].querySelectorAll("td, th");
                                                    
                                                    for (var j = 0; j < cols.length; j++) 
                                                        row.push(cols[j].innerText);
                                                    
                                                    csv.push(row.join(","));        
                                                }

                                                // Download CSV file
                                                downloadCSV(csv.join("\n"), filename);
                                            }
                                        </script>
                                        <a href="#" onclick="exportTableToCSV('ListProduct.csv')"><span class="fa fa-file-excel-o fa-2x"></span></a>
                                    </p>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h4 class="page-content-title">Total Income</h4>
                    <p>Top 10 Order History by Transaction Date.</p>
                    <div class="basic_bootstrap_tbl bootstrap_tbl_no_border">
                        <div class="basic_table table-responsive">
                        <div class="loading-overlay" style="display: none;"><div class="overlay-content">Loading.....</div></div>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order NO</th>
                                        <th>Customer Name</th>
                                        <th>Total Qty</th>
                                        <th>GrandTotal</th>
                                        <th>Date</th>
                                        <th>Payment Method</th>
                                        <th>Status</th>                                       
                                    </tr>
                                </thead>
                                <tbody id="userData">
                                    <?php
                                        foreach($model as $models):
                                    ?>
                                        <tr>
                                            <td><a href="?r=product/invoice&act=0&or=<?= $models->order_no ?>"><?= $models->order_no ?></a></td>
                                            <td>
                                                <?= strtoupper($models->customer) ?>
                                            </td>
                                            <td>
                                                <?= $models->total_qty ?>
                                            </td>
                                            <td>
                                                <?= number_format($models->grandtotal,0,".",".") ?>
                                            </td>
                                            <td>
                                                <?= date('d M Y',strtotime($models->date)) ?>
                                            </td>
                                            <td>
                                                <?php
                                                    if($models->payment == 'Credit'){
                                                            echo "<span class='tag square-tag tag-warning'>Credit</span>";
                                                        }else{
                                                            echo "<span class='tag square-tag tag-success'>Cash / Debit</span>";
                                                        }
                                                ?>

                                            </td>
                                            <td>
                                                <?php
                                                    if($models->status == 1){
                                                        echo "<span class='tag square-tag tag-success'>Success</span>";
                                                    }else{
                                                        echo "<span class='tag square-tag tag-danger'>Void</span>";
                                                    }
                                                ?>

                                            </td>

                                        </tr>
                                        <?php endforeach; ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-xs-12 dashboard_v4_block">
                <div class="content dashboard_v4_project_list">
                    <div class="dashboard-header">
                        <h4 class="page-content-title float-xs-left">Top Period <?= date('M Y') ?>  By Category</h4>
                       
                    </div>
                     <?php
                                                           
                        $query = $connection->createCommand("SELECT COUNT(b.idcategory) category, c.name_category, c.category_color FROM order_detail a JOIN product b ON a.sku = b.sku JOIN category c ON b.idcategory = c.idcategory JOIN `order` d ON a.order_no = d.order_no WHERE MONTH(d.date) = MONTH(now()) AND YEAR(d.date) = YEAR(now()) AND d.status <> 2 GROUP BY c.name_category, c.category_color");
                        $execute = $query->queryAll();
                    ?>  
                    <div class="dashboard-box dashboard_v4_project_block">
                        <table class="table  table-striped">
                            <tbody>
                                 <div class="dashboard-action" style="text-align: center">
                                     <div class="sparkline-pie-gray">
                                       
                                         
                                       <canvas id="myChart" width="350px" height="290px"></canvas>
                                       <script>
                                              $(document).ready(function () {
                                                var pieData = [
                                                     <?php 
                                                        foreach($execute as $executes):
                                                            echo '{
                                                            value : '.$executes['category'].',
                                                            color : "'.$executes['category_color'].'",
                                                            label : "'.substr($executes['name_category'],0,5).': '.$executes['category'].'",
                                                            labelColor : "white",
                                                            labelFontSize : "16"
                                                        },';
                                                        endforeach;
                                                    ?>];

                                                    ctx = $("#myChart").get(0).getContext("2d");
                                                    myNewChart = new Chart(ctx).Pie(pieData);
                                                });
                                       </script>
                                     </div>
                                </div>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-xs-12 dashboard_v4_block2">
                <div class="content dashboard_v4_project_list">
                    <div class="dashboard-header">
                        <h4 class="page-content-title float-xs-left">Income Period <?= date('M Y') ?></h4>
                      
                    </div>

                    <div class="dashboard-box">
                        <div id="dashboard_v4_revenue_chart" class="dashboard_v4_revenue_chart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>


   
            
   
   <?php

    $month = strtoupper(date('M'));
    $sql = $connection->createCommand("SELECT IFNULL(SUM(grandtotal),0) grandtotal FROM `order` WHERE MONTH(date) = MONTH(now()) AND YEAR(date) = YEAR(now()) AND status <> 2");
    $exec = $sql->queryOne();       

    $this->registerJs('  
    ! function(t, e, o) {
    "use strict";
        function a(t, e, a) {
            var i = o(t),
                l = i.data("height");
            l && i.height(l), i.plot(e, a)
        }
        var i = [{
                data: [

                    ["'.$month.'", '.$exec['grandtotal'].'],
                  
                ],
                splines: {
                    show: !0,
                    tension: .45,
                    lineWidth: 4,
                    fill: .1
                }
            }, {
               data: [
                 
                    ["'.$month.'", '.$exec['grandtotal'].'],
                  
                ],
                bars: {
                    show: !0,
                    barWidth: .05,
                    align: "center",
                    lineWidth: 0,
                    fillColor: {
                        colors: [{
                            opacity: .2
                        }, {
                            opacity: .2
                        }]
                    }
                }
            }],
            l = {
                series: {
                    points: {
                        show: !1
                    }
                },
                colors: ["#087380", "#087380"],
                grid: {
                    borderColor: "#eee",
                    borderWidth: 0,
                    hoverable: !0,
                    clickable: !0,
                    backgroundColor: "transparent"
                },
                tooltip: !0,
                tooltipOpts: {
                    content: function(t, e, o) {
                        return "Sell Items : " + o
                    }
                },
                xaxis: {
                    mode: "categories",
                    show: !0
                },
                yaxis: {
                    max: 10000000,
                    show: !0
                },
                legend: {
                    backgroundColor: "transparent",
                    show: !0
                },
                shadowSize: 0
            };
        o("#dashboard_v4_revenue_chart").each(function() {
            a(this, i, l)
        });
    }(document, window, jQuery);');
   ?>