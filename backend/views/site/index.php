<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Dashboard | Admin Stocks';

$this->registerJs('
        $(document).ready(function(){
            var myVar;
           
             $( "#addClick" ).click(function() {
                myFunction(this);
            });
             $( "#sellClick" ).click(function() {
                myFunction(this);
            });
             $( "#stockClick" ).click(function() {
                myFunction(this);
            });             
            $( "#income" ).click(function() {
                myFunction(this);
            });
             $( "#yearly" ).click(function() {
                myFunction(this);
            });
             $( "#profit" ).click(function() {
                myFunction(this);
            }); 
            function myFunction(div) {
                $(".loader").toggle();
                $(div).toggle();

            }    
        });
    ');



     
?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
  <div class="alert alert-success alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4><i class="icon fa fa-check"></i>Saved!</h4>
  <?= Yii::$app->session->getFlash('success') ?>
  </div>
<?php endif; ?>


    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="dashboard_v4_box_block">
                <!--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTable">Primary</button>-->
                <a href="#" data-target="#modalTable" data-toggle="modal" >
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="content">
                            <div class="dashboard_v4_box_icon float-xs-left primary_box">
                                <i class="fa fa-plus-circle"></i>
                            </div>
                            <div class="dashboard_v4_box_title float-xs-right">
                                <h4><?= number_format($category,0,",",","); ?></h4>
                                <p>Add Category</p>
                            </div>
                        </div>
                    </div>
                </a>
                <a id="addClick" href="?r=product/index">
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="content">
                            <div class="dashboard_v4_box_icon float-xs-left warning_box">
                                <i class="fa fa-tag"></i>
                            </div>
                            <div class="dashboard_v4_box_title float-xs-right">
                                <h4><?= number_format($product,0,",",","); ?></h4>
                                <p>Add Products</p>
                            </div>
                        </div>
                    </div>
                </a>
                <a id="sellClick" href="?r=product/seller">
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="content">
                            <div class="dashboard_v4_box_icon float-xs-left success_box">
                                <i class="fa fa-shopping-bag"></i>
                            </div>
                            <div class="dashboard_v4_box_title float-xs-right">
                                <h4><?= number_format($order,0,",",","); ?></h4>
                                <p>Total Sell</p>
                            </div>
                        </div>
                    </div>
                </a>
                <a id="stockClick" href="?r=product/stock">
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="content">
                            <div class="dashboard_v4_box_icon float-xs-left danger_box">
                                <i class="fa fa-book"></i>
                            </div>
                            <div class="dashboard_v4_box_title float-xs-right">
                                <h4><?= number_format($oos,0,",",","); ?></h4>
                                <p>Out Of Stock</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-xs-12 dashboard_v4_block">
                <div class="content dashboard_v4_project_list">
                    <div class="dashboard-header">
                        <h4 class="page-content-title float-xs-left">Your yearly revenues</h4>
                        <div class="dashboard-action">
                            <ul class="right-action float-xs-right">
                                <li data-widget="collapse"><a href="javascript:void(0)" aria-hidden="true"><span class="icon_minus-06" aria-hidden="true"></span></a></li>
                                <li data-widget="close"><a href="javascript:void(0)"><span class="icon_close" aria-hidden="true"></span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="dashboard-box dashboard_v4_project_block">
                        <table class="table  table-striped">
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="stackline-bar">3,5,7,9,4,6,5,8</div>
                                    </td>
                                    <td>
                                        <h6><a id="income" href="?r=report/index">Total income</a></h6>
                                        <p>Total amount of income earned annually.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="sparkline-line">1,5,6,9,4,8,5,4</div>
                                    </td>
                                    <td>
                                        <h6><a id="yearly"  href="?r=report/sell">Yearly sell</a></h6>
                                        <p>Businesses do their accounting on a sell.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="sparkline-inlinerange">5,3,6,4,5,3,5,7</div>
                                    </td>
                                    <td>
									
                                        <h6><a id="profit"  href="?r=report/profit">Profit / Loss </a></h6>
                                        <p>Revenue minus or plus the cost of goods sold.</p>
                                    </td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-xs-12 dashboard_v4_block2">
                <div class="content dashboard_v4_project_list">
                    <div class="dashboard-header">
                        <h4 class="page-content-title float-xs-left">Selling Items analysis Year <?= date('Y')?></h4>
                        <div class="dashboard-action">
                            <ul class="right-action float-xs-right">
                                <li data-widget="collapse"><a href="javascript:void(0)" aria-hidden="true"><span class="icon_minus-06" aria-hidden="true"></span></a></li>
                                <li data-widget="close"><a href="javascript:void(0)"><span class="icon_close" aria-hidden="true"></span></a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="dashboard-box">
                        <div id="dashboard_v4_revenue_chart" class="dashboard_v4_revenue_chart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--MODAL CATEGORY    -->
    
    <div class="modal fade" id="modalTable" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title text-primary">Add Category</h4>
                </div>
                <div class="modal-body">
                    <?php $form = ActiveForm::begin(); ?>
                        <div class="form-group">
                            <label for="recipient-name" class="form-control-label">Category Name</label>
                            <?= $form->field($model, 'name_category')->textInput(['maxlength' => true])->label(false) ?>                            
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="form-control-label">Color</label>
                            <input type="color" name="color" value="" class="form-control">
                         </div>
                         <div class="form-group">
                            <?= Html::submitButton('Save changes',['class' =>'btn btn-primary']) ?>
                        </div>
                     <?php ActiveForm::end(); ?>
                   
                   
                   <table class="table">
                        <thead>
                            <tr>                                
                                <th>Category Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($lookUp as $lookUps):                                                                    
                            ?>
                            <tr>                                
                                <td><?= $lookUps->name_category?></td>                                
                            </tr>
                           <?php endforeach; ?>
                        </tbody>
                    </table>

                </div>
               
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
   
   <?php
    $connection = \Yii::$app->db;
    
    $sql_jan = $connection->createCommand("SELECT IFNULL(SUM(total_qty),0) qty FROM `order` WHERE MONTH(date) = 1 AND YEAR(date) = YEAR(now())  AND status <> 2");
    $jan = $sql_jan->queryOne();   
   
    $sql_feb = $connection->createCommand("SELECT IFNULL(SUM(total_qty),0) qty FROM `order` WHERE MONTH(date) = 2 AND YEAR(date) = YEAR(now())  AND status <> 2");
    $feb = $sql_feb->queryOne();   
   
    $sql_mar = $connection->createCommand("SELECT IFNULL(SUM(total_qty),0) qty FROM `order` WHERE MONTH(date) = 3 AND YEAR(date) = YEAR(now())  AND status <> 2");
    $mar = $sql_mar->queryOne();   
    
    $sql_apr = $connection->createCommand("SELECT IFNULL(SUM(total_qty),0) qty FROM `order` WHERE MONTH(date) = 4 AND YEAR(date) = YEAR(now())  AND status <> 2");
    $apr = $sql_apr->queryOne();   
    
    $sql_may = $connection->createCommand("SELECT IFNULL(SUM(total_qty),0) qty FROM `order` WHERE MONTH(date) = 5 AND YEAR(date) = YEAR(now())  AND status <> 2");
    $may = $sql_may->queryOne();   
    
    $sql_jun = $connection->createCommand("SELECT IFNULL(SUM(total_qty),0) qty FROM `order` WHERE MONTH(date) = 6 AND YEAR(date) = YEAR(now())  AND status <> 2");
    $jun = $sql_jun->queryOne();   
   
    $sql_jul = $connection->createCommand("SELECT IFNULL(SUM(total_qty),0) qty FROM `order` WHERE MONTH(date) = 7 AND YEAR(date) = YEAR(now())  AND status <> 2");
    $jul = $sql_jul->queryOne();   
    
    $sql_agu = $connection->createCommand("SELECT IFNULL(SUM(total_qty),0) qty FROM `order` WHERE MONTH(date) = 8 AND YEAR(date) = YEAR(now())  AND status <> 2");
    $agu = $sql_agu->queryOne();   
    
    $sql_sep = $connection->createCommand("SELECT IFNULL(SUM(total_qty),0) qty FROM `order` WHERE MONTH(date) = 9 AND YEAR(date) = YEAR(now())  AND status <> 2");
    $sep = $sql_sep->queryOne();   
    
    $sql_okt = $connection->createCommand("SELECT IFNULL(SUM(total_qty),0) qty FROM `order` WHERE MONTH(date) = 10 AND YEAR(date) = YEAR(now())  AND status <> 2");
    $okt = $sql_okt->queryOne();   
   
    $sql_nov = $connection->createCommand("SELECT IFNULL(SUM(total_qty),0) qty FROM `order` WHERE MONTH(date) = 11 AND YEAR(date) = YEAR(now())  AND status <> 2");
    $nov = $sql_nov->queryOne();   
    
    $sql_dec = $connection->createCommand("SELECT IFNULL(SUM(total_qty),0) qty FROM `order` WHERE MONTH(date) = 12 AND YEAR(date) = YEAR(now())  AND status <> 2");
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
                    ["JAN", '.$jan['qty'].'],
                    ["FEB", '.$feb['qty'].'],
                    ["MAR", '.$mar['qty'].'],
                    ["APR", '.$apr['qty'].'],
                    ["MAY", '.$may['qty'].'],
                    ["JUN", '.$jun['qty'].'],
                    ["JUL", '.$jul['qty'].'],
                    ["AUG", '.$agu['qty'].'],
                    ["SEP", '.$sep['qty'].'],
                    ["OCT", '.$okt['qty'].'],
                    ["NOV", '.$nov['qty'].'],
                    ["DEC", '.$dec['qty'].']
                ],
                splines: {
                    show: !0,
                    tension: .45,
                    lineWidth: 4,
                    fill: .1
                }
            }, {
               data: [
                    ["JAN", '.$jan['qty'].'],
                    ["FEB", '.$feb['qty'].'],
                    ["MAR", '.$mar['qty'].'],
                    ["APR", '.$apr['qty'].'],
                    ["MAY", '.$may['qty'].'],
                    ["JUN", '.$jun['qty'].'],
                    ["JUL", '.$jul['qty'].'],
                    ["AUG", '.$agu['qty'].'],
                    ["SEP", '.$sep['qty'].'],
                    ["OCT", '.$okt['qty'].'],
                    ["NOV", '.$nov['qty'].'],
                    ["DEC", '.$dec['qty'].']
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
                    max: 200,
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