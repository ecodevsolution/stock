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
        
           
            <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-xs-12 dashboard_v4_block">
                <div class="content dashboard_v4_project_list">
                    <div class="dashboard-header">
                        <h4 class="page-content-title float-xs-left">Top Period <?= date('M Y') ?>  By Category</h4>
                       
                    </div>
                     <?php
                                                           
                        $query = $connection->createCommand("SELECT COUNT(b.idcategory) category, c.name_category, c.category_color FROM order_detail a JOIN product b ON a.sku = b.sku JOIN category c ON b.idcategory = c.idcategory JOIN `order` d ON a.order_no = d.order_no WHERE YEAR(d.date) = YEAR(now()) AND d.status <> 2 GROUP BY c.name_category, c.category_color");
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
                                                            label : "'.$executes['name_category'].': '.$executes['category'].'",
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
    $connection = \Yii::$app->db;
    
    $sql_jan = $connection->createCommand("SELECT IFNULL(SUM(grandtotal),0) grandtotal FROM `order` WHERE MONTH(date) = 1 AND YEAR(date) = YEAR(now()) AND status <> 2");
    $jan = $sql_jan->queryOne();   
   
    $sql_feb = $connection->createCommand("SELECT  IFNULL(SUM(grandtotal),0) grandtotal FROM `order` WHERE MONTH(date) = 2 AND YEAR(date) = YEAR(now()) AND status <> 2");
    $feb = $sql_feb->queryOne();   
   
    $sql_mar = $connection->createCommand("SELECT IFNULL(SUM(grandtotal),0) grandtotal FROM `order` WHERE MONTH(date) = 3 AND YEAR(date) = YEAR(now()) AND status <> 2");
    $mar = $sql_mar->queryOne();   
    
    $sql_apr = $connection->createCommand("SELECT IFNULL(SUM(grandtotal),0) grandtotal FROM `order` WHERE MONTH(date) = 4 AND YEAR(date) = YEAR(now()) AND status <> 2");
    $apr = $sql_apr->queryOne();   
    
    $sql_may = $connection->createCommand("SELECT IFNULL(SUM(grandtotal),0) grandtotal FROM `order` WHERE MONTH(date) = 5 AND YEAR(date) = YEAR(now()) AND status <> 2");
    $may = $sql_may->queryOne();   
    
    $sql_jun = $connection->createCommand("SELECT IFNULL(SUM(grandtotal),0) grandtotal FROM `order` WHERE MONTH(date) = 6 AND YEAR(date) = YEAR(now()) AND status <> 2");
    $jun = $sql_jun->queryOne();   
   
    $sql_jul = $connection->createCommand("SELECT IFNULL(SUM(grandtotal),0) grandtotal FROM `order` WHERE MONTH(date) = 7 AND YEAR(date) = YEAR(now()) AND status <> 2");
    $jul = $sql_jul->queryOne();   
    
    $sql_agu = $connection->createCommand("SELECT IFNULL(SUM(grandtotal),0) grandtotal FROM `order` WHERE MONTH(date) = 8 AND YEAR(date) = YEAR(now()) AND status <> 2");
    $agu = $sql_agu->queryOne();   
    
    $sql_sep = $connection->createCommand("SELECT IFNULL(SUM(grandtotal),0) grandtotal FROM `order` WHERE MONTH(date) = 9 AND YEAR(date) = YEAR(now()) AND status <> 2");
    $sep = $sql_sep->queryOne();   
    
    $sql_okt = $connection->createCommand("SELECT IFNULL(SUM(grandtotal),0) grandtotal FROM `order` WHERE MONTH(date) = 10 AND YEAR(date) = YEAR(now()) AND status <> 2");
    $okt = $sql_okt->queryOne();   
   
    $sql_nov = $connection->createCommand("SELECT IFNULL(SUM(grandtotal),0) grandtotal FROM `order` WHERE MONTH(date) = 11 AND YEAR(date) = YEAR(now()) AND status <> 2");
    $nov = $sql_nov->queryOne();   
    
    $sql_dec = $connection->createCommand("SELECT IFNULL(SUM(grandtotal),0) grandtotal FROM `order` WHERE MONTH(date) = 12 AND YEAR(date) = YEAR(now()) AND status <> 2");
    $dec = $sql_dec->queryOne();   

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
                    ["JAN", '.$jan['grandtotal'].'],
                    ["FEB", '.$feb['grandtotal'].'],
                    ["MAR", '.$mar['grandtotal'].'],
                    ["APR", '.$apr['grandtotal'].'],
                    ["MAY", '.$may['grandtotal'].'],
                    ["JUN", '.$jun['grandtotal'].'],
                    ["JUL", '.$jul['grandtotal'].'],
                    ["AUG", '.$agu['grandtotal'].'],
                    ["SEP", '.$sep['grandtotal'].'],
                    ["OCT", '.$okt['grandtotal'].'],
                    ["NOV", '.$nov['grandtotal'].'],
                    ["DEC", '.$dec['grandtotal'].']
                ],
                splines: {
                    show: !0,
                    tension: .45,
                    lineWidth: 4,
                    fill: .1
                }
            }, {
               data: [
                    ["JAN", '.$jan['grandtotal'].'],
                    ["FEB", '.$feb['grandtotal'].'],
                    ["MAR", '.$mar['grandtotal'].'],
                    ["APR", '.$apr['grandtotal'].'],
                    ["MAY", '.$may['grandtotal'].'],
                    ["JUN", '.$jun['grandtotal'].'],
                    ["JUL", '.$jul['grandtotal'].'],
                    ["AUG", '.$agu['grandtotal'].'],
                    ["SEP", '.$sep['grandtotal'].'],
                    ["OCT", '.$okt['grandtotal'].'],
                    ["NOV", '.$nov['grandtotal'].'],
                    ["DEC", '.$dec['grandtotal'].']
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
   
   