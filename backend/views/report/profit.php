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

            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 dashboard_v4_block2" style="width:100%">
                <div class="content dashboard_v4_project_list">
                    <div class="dashboard-header">
                        <h4 class="page-content-title float-xs-left">Profit/Loss Period <?= date('M Y') ?></h4>
						<?php
							$connection = \Yii::$app->db;
							
							$sqlxx = $connection->createCommand("SELECT IFNULL(SUM(grandtotal),0) sell, 
													(SELECT IFNULL(SUM(qty * price),0) FROM purchase_order  
														WHERE MONTH(date) = MONTH(now()) AND YEAR(date) = YEAR(now())) buy,
														
								IFNULL(SUM(grandtotal),0) - 	(SELECT IFNULL(SUM(qty * price),0) FROM purchase_order  
														WHERE MONTH(date) = MONTH(now()) AND YEAR(date) = YEAR(now())) TotalProfit
								FROM `order` 
								WHERE MONTH(date) = MONTH(now()) AND YEAR(date) = YEAR(now()) AND 
								MONTH(date) = MONTH(now()) AND YEAR(date) = YEAR(now())  AND status <> 2");
							$xx = $sqlxx->queryOne(); 
						?>
						<h5> &nbsp;  (<i style="font-size:12px">in <?= number_format($xx['sell'],0,".",".") ?></i>)  &nbsp; (<i style="font-size:12px">out <?= number_format($xx['buy'],0,".",".") ?></i>) </h5>
                    </div>

                    <div class="dashboard-box" >
                        <div id="dashboard_v4_revenue_chart" class="dashboard_v4_revenue_chart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php
    $connection = \Yii::$app->db;
    
    $sql_jan = $connection->createCommand("SELECT IFNULL(SUM(grandtotal),0) sell, 
							(SELECT IFNULL(SUM(qty * price),0) FROM purchase_order  
								WHERE MONTH(date) = 1 AND YEAR(date) = YEAR(now())) buy,
								
		IFNULL(SUM(grandtotal),0) - 	(SELECT IFNULL(SUM(qty * price),0) FROM purchase_order  
								WHERE MONTH(date) = 1 AND YEAR(date) = YEAR(now())) TotalProfit
		FROM `order` 
		WHERE MONTH(date) = 1 AND YEAR(date) = YEAR(now()) AND 
		MONTH(date) = 1 AND YEAR(date) = YEAR(now())  AND status <> 2");
    $jan = $sql_jan->queryOne();   
   
    $sql_feb = $connection->createCommand("SELECT IFNULL(SUM(grandtotal),0) sell, 
							(SELECT IFNULL(SUM(qty * price),0) FROM purchase_order  
								WHERE MONTH(date) = 2 AND YEAR(date) = YEAR(now())) buy,
								
		IFNULL(SUM(grandtotal),0) - 	(SELECT IFNULL(SUM(qty * price),0) FROM purchase_order  
								WHERE MONTH(date) = 2 AND YEAR(date) = YEAR(now())) TotalProfit
		FROM `order` 
		WHERE MONTH(date) = 2 AND YEAR(date) = YEAR(now()) AND 
		MONTH(date) = 2 AND YEAR(date) = YEAR(now())  AND status <> 2");
    $feb = $sql_feb->queryOne();   
   
    $sql_mar = $connection->createCommand("SELECT IFNULL(SUM(grandtotal),0) sell, 
							(SELECT IFNULL(SUM(qty * price),0) FROM purchase_order  
								WHERE MONTH(date) = 3 AND YEAR(date) = YEAR(now())) buy,
								
		IFNULL(SUM(grandtotal),0) - 	(SELECT IFNULL(SUM(qty * price),0) FROM purchase_order  
								WHERE MONTH(date) = 3 AND YEAR(date) = YEAR(now())) TotalProfit
		FROM `order` 
		WHERE MONTH(date) = 3 AND YEAR(date) = YEAR(now()) AND 
		MONTH(date) = 3 AND YEAR(date) = YEAR(now())  AND status <> 2");
    $mar = $sql_mar->queryOne();   
    
    $sql_apr = $connection->createCommand("SELECT IFNULL(SUM(grandtotal),0) sell, 
							(SELECT IFNULL(SUM(qty * price),0) FROM purchase_order  
								WHERE MONTH(date) = 4 AND YEAR(date) = YEAR(now())) buy,
								
		IFNULL(SUM(grandtotal),0) - 	(SELECT IFNULL(SUM(qty * price),0) FROM purchase_order  
								WHERE MONTH(date) = 4 AND YEAR(date) = YEAR(now())) TotalProfit
		FROM `order` 
		WHERE MONTH(date) = 4 AND YEAR(date) = YEAR(now()) AND 
		MONTH(date) = 4 AND YEAR(date) = YEAR(now())  AND status <> 2");
    $apr = $sql_apr->queryOne();   
    
    $sql_may = $connection->createCommand("SELECT IFNULL(SUM(grandtotal),0) sell, 
							(SELECT IFNULL(SUM(qty * price),0) FROM purchase_order  
								WHERE MONTH(date) = 5 AND YEAR(date) = YEAR(now())) buy,
								
		IFNULL(SUM(grandtotal),0) - 	(SELECT IFNULL(SUM(qty * price),0) FROM purchase_order  
								WHERE MONTH(date) = 5 AND YEAR(date) = YEAR(now())) TotalProfit
		FROM `order` 
		WHERE MONTH(date) = 5 AND YEAR(date) = YEAR(now()) AND 
		MONTH(date) = 5 AND YEAR(date) = YEAR(now())  AND status <> 2");
    $may = $sql_may->queryOne();   
    
    $sql_jun = $connection->createCommand("SELECT IFNULL(SUM(grandtotal),0) sell, 
							(SELECT IFNULL(SUM(qty * price),0) FROM purchase_order  
								WHERE MONTH(date) = 6 AND YEAR(date) = YEAR(now())) buy,
								
		IFNULL(SUM(grandtotal),0) - 	(SELECT IFNULL(SUM(qty * price),0) FROM purchase_order  
								WHERE MONTH(date) = 6 AND YEAR(date) = YEAR(now())) TotalProfit
		FROM `order` 
		WHERE MONTH(date) = 6 AND YEAR(date) = YEAR(now()) AND 
		MONTH(date) = 6 AND YEAR(date) = YEAR(now())  AND status <> 2");
    $jun = $sql_jun->queryOne();   
   
    $sql_jul = $connection->createCommand("SELECT IFNULL(SUM(grandtotal),0) sell, 
							(SELECT IFNULL(SUM(qty * price),0) FROM purchase_order  
								WHERE MONTH(date) = 7 AND YEAR(date) = YEAR(now())) buy,
								
		IFNULL(SUM(grandtotal),0) - 	(SELECT IFNULL(SUM(qty * price),0) FROM purchase_order  
								WHERE MONTH(date) = 7 AND YEAR(date) = YEAR(now())) TotalProfit
		FROM `order` 
		WHERE MONTH(date) = 7 AND YEAR(date) = YEAR(now()) AND 
		MONTH(date) = 7 AND YEAR(date) = YEAR(now())  AND status <> 2");
    $jul = $sql_jul->queryOne();   
    
    $sql_agu = $connection->createCommand("SELECT IFNULL(SUM(grandtotal),0) sell, 
							(SELECT IFNULL(SUM(qty * price),0) FROM purchase_order  
								WHERE MONTH(date) = 8 AND YEAR(date) = YEAR(now())) buy,
								
		IFNULL(SUM(grandtotal),0) - 	(SELECT IFNULL(SUM(qty * price),0) FROM purchase_order  
								WHERE MONTH(date) = 8 AND YEAR(date) = YEAR(now())) TotalProfit
		FROM `order` 
		WHERE MONTH(date) = 8 AND YEAR(date) = YEAR(now()) AND 
		MONTH(date) = 8 AND YEAR(date) = YEAR(now())  AND status <> 2");
    $agu = $sql_agu->queryOne();   
    
    $sql_sep = $connection->createCommand("SELECT IFNULL(SUM(grandtotal),0) sell, 
							(SELECT IFNULL(SUM(qty * price),0) FROM purchase_order  
								WHERE MONTH(date) = 9 AND YEAR(date) = YEAR(now())) buy,
								
		IFNULL(SUM(grandtotal),0) - 	(SELECT IFNULL(SUM(qty * price),0) FROM purchase_order  
								WHERE MONTH(date) = 9 AND YEAR(date) = YEAR(now())) TotalProfit
		FROM `order` 
		WHERE MONTH(date) = 9 AND YEAR(date) = YEAR(now()) AND 
		MONTH(date) = 9 AND YEAR(date) = YEAR(now())  AND status <> 2");
    $sep = $sql_sep->queryOne();   
    
    $sql_okt = $connection->createCommand("SELECT IFNULL(SUM(grandtotal),0) sell, 
							(SELECT IFNULL(SUM(qty * price),0) FROM purchase_order  
								WHERE MONTH(date) = 10 AND YEAR(date) = YEAR(now())) buy,
								
		IFNULL(SUM(grandtotal),0) - 	(SELECT IFNULL(SUM(qty * price),0) FROM purchase_order  
								WHERE MONTH(date) = 10 AND YEAR(date) = YEAR(now())) TotalProfit
		FROM `order` 
		WHERE MONTH(date) = 10 AND YEAR(date) = YEAR(now()) AND 
		MONTH(date) = 10 AND YEAR(date) = YEAR(now())  AND status <> 2");
    $okt = $sql_okt->queryOne();   
   
    $sql_nov = $connection->createCommand("SELECT IFNULL(SUM(grandtotal),0) sell, 
							(SELECT IFNULL(SUM(qty * price),0) FROM purchase_order  
								WHERE MONTH(date) = 11 AND YEAR(date) = YEAR(now())) buy,
								
		IFNULL(SUM(grandtotal),0) - 	(SELECT IFNULL(SUM(qty * price),0) FROM purchase_order  
								WHERE MONTH(date) = 11 AND YEAR(date) = YEAR(now())) TotalProfit
		FROM `order` 
		WHERE MONTH(date) = 11 AND YEAR(date) = YEAR(now()) AND 
		MONTH(date) = 11 AND YEAR(date) = YEAR(now())  AND status <> 2");
    $nov = $sql_nov->queryOne();   
    
    $sql_dec = $connection->createCommand("SELECT IFNULL(SUM(grandtotal),0) sell, 
							(SELECT IFNULL(SUM(qty * price),0) FROM purchase_order  
								WHERE MONTH(date) = 12 AND YEAR(date) = YEAR(now())) buy,
								
		IFNULL(SUM(grandtotal),0) - 	(SELECT IFNULL(SUM(qty * price),0) FROM purchase_order  
								WHERE MONTH(date) = 12 AND YEAR(date) = YEAR(now())) TotalProfit
		FROM `order` 
		WHERE MONTH(date) = 12 AND YEAR(date) = YEAR(now()) AND 
		MONTH(date) = 12 AND YEAR(date) = YEAR(now())  AND status <> 2");
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
                    ["JAN", '.$jan['TotalProfit'].'],
                    ["FEB", '.$feb['TotalProfit'].'],
                    ["MAR", '.$mar['TotalProfit'].'],
                    ["APR", '.$apr['TotalProfit'].'],
                    ["MAY", '.$may['TotalProfit'].'],
                    ["JUN", '.$jun['TotalProfit'].'],
                    ["JUL", '.$jul['TotalProfit'].'],
                    ["AUG", '.$agu['TotalProfit'].'],
                    ["SEP", '.$sep['TotalProfit'].'],
                    ["OCT", '.$okt['TotalProfit'].'],
                    ["NOV", '.$nov['TotalProfit'].'],
                    ["DEC", '.$dec['TotalProfit'].']
                ],
                splines: {
                    show: !0,
                    tension: .45,
                    lineWidth: 4,
                    fill: .1
                }
            }, {
               data: [
                    ["JAN", '.$jan['TotalProfit'].'],
                    ["FEB", '.$feb['TotalProfit'].'],
                    ["MAR", '.$mar['TotalProfit'].'],
                    ["APR", '.$apr['TotalProfit'].'],
                    ["MAY", '.$may['TotalProfit'].'],
                    ["JUN", '.$jun['TotalProfit'].'],
                    ["JUL", '.$jul['TotalProfit'].'],
                    ["AUG", '.$agu['TotalProfit'].'],
                    ["SEP", '.$sep['TotalProfit'].'],
                    ["OCT", '.$okt['TotalProfit'].'],
                    ["NOV", '.$nov['TotalProfit'].'],
                    ["DEC", '.$dec['TotalProfit'].']
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
                    max: 5000000,
                    min: -5000000,
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