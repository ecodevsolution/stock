<?php
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    
    ?>
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="row">
        <div class="dashboard_v4_box_block">
            <?php
                include "menu.php";
                
                 $root = '@web';
                
                
                 $this->registerJsFile($root."/components/plugins/datatables/media/js/jquery.dataTables.min.js",
                 ['depends' => [\yii\web\JqueryAsset::className()],
                 'position' => View::POS_END]);
                 $this->registerJsFile($root."/components/plugins/datatables/media/js/dataTables.bootstrap4.min.js",
                 ['depends' => [\yii\web\JqueryAsset::className()],
                 'position' => View::POS_END]);
                 $this->registerJsFile($root."/components/plugins/sweetalert2/dist/sweetalert2.min.js",
                 ['depends' => [\yii\web\JqueryAsset::className()],
                 'position' => View::POS_END]);
                 $this->registerJsFile($root."/components/js/global/sweetalert.js",
                 ['depends' => [\yii\web\JqueryAsset::className()],
                 'position' => View::POS_END]);

              
                ?>
        </div>
    </div>
    
    <section id="main" class="container-fluid">     
        <div class="row">        
            <section id="content-wrapper" class="content-view-order">            
                <div class="content datatable-width">                
                    <div class="action-vieworder">
                        <div class="nav-tab-horizontal">
                            <div class="tab-content">
                            <?php
                                 $urut = mt_rand(10,999); 
                            ?>
                            <input type="text"  name="customer"  id ="customer" style="float:left" value="Customer <?= $urut ?>">                            
                            <a href="#" data-target="#modalTable" data-toggle="modal" style="float:right" class="btn-hover-animation hvr-float-shadow" >Pick Item</a>                                  
                                <div class="tab-pane active btn-margin" id="detail" role="tabpanel">
                                    <table data-plugin="datatable" data-scroll-x="true" class="table table-striped table-hover display nowrap content-order-information">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                                <th>Tax</th>
                                                <th>Discount</th>
                                                <th>Total price</th>
                                                <th colspan="2">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                foreach($modelOrder as $modelOrders):

                                                        $connection = \Yii::$app->db;
                                                        $sql = $connection->createCommand("SELECT tmp_sku, SUM(tmp_qty) qty, tmp_color, tmp_size FROM 
                                                                                                  temp_order_detail where tmp_sku = '$modelOrders->tmp_sku' AND  
                                                                                                                                  tmp_color = '$modelOrders->tmp_color' AND  
                                                                                                                                  tmp_size = '$modelOrders->tmp_size' GROUP BY tmp_sku ,tmp_color, tmp_discount, tmp_size ");
                                                        $check = $sql->queryOne();



                                                        $query = $connection->createCommand("SELECT * FROM product_attribute where sku = '$modelOrders->tmp_sku' AND  
                                                                                                                                  color = '$modelOrders->tmp_color' AND  
                                                                                                                                  size = '$modelOrders->tmp_size'");
                                                        $stock = $query->queryOne();
                                                        
                                                        $alert="in stock";
                                                        $class = "success";
                                                        if($check['qty'] > $stock['stock']){
                                                            $alert="Stock not Available !";
                                                            $class="danger";
                                                        }                                                        
        
                                            ?>
                                            <tr>
                                                <td><?= $modelOrders->product->product_name; ?><p style="font-size:10px">Color:  <span style="background-color:<?= $modelOrders->tmp_color?>;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>,  Size:  <?= $modelOrders->tmp_size ?></p></td>
                                                <td><?= number_format($modelOrders->tmp_price,0,".","."); ?></td>
                                                <td><?= $modelOrders->tmp_qty; ?></td>
                                                <td>0% </td>
                                                <td><?= number_format($modelOrders->tmp_discount,0,".","."); ?></td>
                                                <td><?= number_format($modelOrders->tmp_total_price,0,".","."); ?></td>
                                                <td><span class="tag tag-<?= $class ?>"><?= $alert; ?></span></td>
                                                 <td>                                            
                                                    <a href="#" class="button" title="void transaction" data-id="<?= $modelOrders->tmp_id ?>"><span class="basic_table_icon  warning confirm"><i class="icon icon_trash"></i></span></a>
                                                </td>
                                            </tr>
                                            <?php
                                                endforeach;
                                            ?>
                                          
                                        </tbody>
                                    </table>

                                    <div class="content sub-total-order text-xs-right">
                                        <div class="total-order-view">
                                            <div class="float-xs-left title-order-view">Sub total :</div>
                                            <div class="float-xs-right amount-order-view"><?= number_format($subTotal,0,".",".") ?></div>
                                        </div>
                                        <div class="divider10"></div>
                                        <div class="total-order-view">
                                            <div class="float-xs-left title-order-view">Tax :</div>
                                            <div class="float-xs-right amount-order-view">0%</div>
                                        </div>
                                        <div class="divider10"></div>
                                        <div class="total-order-view">
                                            <div class="float-xs-left title-order-view">Grand total :</div>
                                            <div class="float-xs-right text-primary amount-order-view"><?= number_format($subTotal,0,".",".") ?></div>
                                        </div>
                                        <div class="divider10"></div>
                                        <div class="total-order-view">
                                            <div class="float-xs-left title-order-view">Discount :</div>
                                            <div class="float-xs-right amount-order-view"><?= number_format($disc,0,".",".") ?></div>
                                        </div>
                                        <div class="divider10"></div>
                                        <div class="total-order-view">
                                            <div class="float-xs-left title-order-view"><b>Total paid :</b></div>
                                            <div class="float-xs-right text-primary amount-order-view"><b><?= number_format($total_paid,0,".",".") ?></b></div>
                                        </div>
                                        
                                        <div class="divider10"></div>
                                        <div class="total-order-view">
                                            <select name="payment" id="payment" class="form-control">
                                                <option value="Cash"> Cash/Debit </option>
                                                <option value="Credit"> Credit </option>
                                            </select>
                                            <!--<div class="float-xs-right text-primary amount-order-view"><b><?= number_format($total_paid,0,".",".") ?></b></div>-->
                                        </div>
                                        <button class="btn btn-primary float-xs-right site-btn invoice-print warning confirm"><i aria-hidden="true" class="fa fa-shopping-bag"></i>Checkout</button>
                                        <!--<a class="btn btn-primary float-xs-right site-btn invoice-print"><i aria-hidden="true" data-icon=""></i>Print</a>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </section>
</div>


<!-- BEGIN MODAL -->
<div class="modal fade" id="modalTable" tabindex="-1" role="dialog" aria-hidden="true">
    
    <div class="content content-datatable datatable-width">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>       
        <div class="row">
                  
            <div class="col-md-12">
             <div class="modal-body">
                <table data-plugin="datatable" data-responsive="true" class="table table-striped table-hover dt-responsive">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Product</th>
                            <th>Category</th>                                                        
                            <th>Brand</th>
                            <th>Price</th>                            
                        </tr>
                    </thead>
                    <tbody>
                            <?php foreach($product as $products): ?>
                                <tr onclick="document.location = '#';" data-id="<?= $products->sku ?>" data-target="#ModalProduct" class="open-AddBookDialog" data-toggle="modal">
                                    <td><?= $products->sku ?></td>
                                    <td><?= $products->product_name ?></td>
                                    <td><?= $products->category->name_category ?></td>
                                    <td><?= $products->brand->brand ?></td>
                                    <td><?= number_format($products->price_sell,0,".",".") ?></td>                                
                                </tr>
                            <?php endforeach; ?>                                                         
                    </tbody>
                </table>
                </div>
            </div>            
            
        </div>
    </div>
</div>
<!--END MODAL-->
<!-- BEGIN MODAL -->
<div class="modal fade" id="ModalProduct" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-primary">Add Items</h4>
            </div>
            <div class="modal-body">
               <?php $form = ActiveForm::begin(); ?>
                    <div class="form-group">
                        <label for="recipient-name" class="form-control-label">SKU</label>
                        <input type="text" class="form-control" id="sku" name="sku" readonly=true>
                    </div>

                    <div class="form-group">
                        <label for="recipient-name" class="form-control-label">Size</label>
                        <select name="size" class="form-control" required id="size">
                            <option value="">- Choose -</option>
                        </select>
                    </div>

                    
                    <div id="color"></div>

                    <div class="form-group">
                        <label for="recipient-name" class="form-control-label">Discount</label>
                        <input type="text" class="form-control" required name="discount" value="0">
                    </div>                   
                    <div class="form-group">
                        <label for="recipient-name" class="form-control-label">Qty</label>
                        <input type="text" class="form-control" required name="qty">                        
                    </div>

                     

                     <div id = "loading" style="text-align:center"></div>
                   
                
				    <?= Html::submitButton('Add To Cart', ['class' => 'btn btn-primary']) ?>
			
                   
                    <br/> <br/>
               <?php ActiveForm::end(); ?>             
            </div>            
        </div>
    </div>
</div>
<!--END MODAL-->

<?php 
    $this->registerJs('
        $(document).on("click", ".open-AddBookDialog", function () {
            var myBookId = $(this).data("id");
            $(".modal-body #sku").val( myBookId );
        });
    ');


    $this->registerJs("
        (function($) {

           function loading() {
                 $('#loading').html(\"<img src='components/image/ajax_loader.gif' style='width:30px;height:30px' />\").fadeIn('fast');
             }

             function loading_() {
                 $('#loading').fadeOut('fast');
             }
            $(document).ready(function() {
                $('.open-AddBookDialog').click(function() {
                  
                    var id = $(this).data('id');
                    if(id){
                        var dataString = 'id=' + id;
                        loading();
                        $.ajax({
                            type: 'GET',
                            url: '?r=product/items',
                            data: dataString,
                            cache: false,
                            success: function(html) {
                                loading_();
                                $('#size').html(html);                                
                                
                            }
                        });
                     }
                })
            })
 

             $(document).ready(function() {
                $('#size').change(function() {
                    var size = $(this).val();  
                    var product = document.getElementById('sku').value;                 
                    var dataString = 'size=' + size + '&p=' +product;  
                    loading();                  
                    $.ajax({
                        type: 'GET',
                        url: '?r=product/color',
                        data: dataString,
                        cache: false,
                        success: function(html) {
                             loading_();
                            $('#color').html(html);     
                        }
                    });
                });

            });


            $('.warning.confirm').on('click', function () {
                var customer = document.getElementById('customer').value;   
                var payment = document.getElementById('payment').value;   
                swal({
                    title: 'Are you sure to Checkout ?',
                    text: 'You won\'t be able to revert this!',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonClass: \"btn btn-primary flat-buttons waves-effect waves-button\",
                    cancelButtonClass: \"btn btn-danger flat-buttons waves-effect waves-button\",
                    buttonsStyling: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, I Want to Checkout!'
                }).then(function () {
                    swal({
                        title: 'Shopping Cart!',
                        type: 'success',
                        text: 'Thank You For Shopping!',
                        confirmButtonClass: \"btn btn-primary flat-buttons waves-effect waves-button\",
                        cancelButtonClass: \"btn btn-danger flat-buttons waves-effect waves-button\",
                        buttonsStyling: false
                    }).then(function() {
                        location.href = '?r=product/invoice&act=1&c='+customer+'&p='+payment;
                    });
                }).catch(swal.noop)
            })
           
        })(jQuery);  

        ");        
?>

<?php

$this->registerJs("
        (function($) {

           $(document).on('click', '.button', function (e) {
            e.preventDefault();
            var id = $(this).data('id');                             
            swal({
                title: 'Are you sure  ?',
                text: 'You won\'t be able to revert this!',
                type: 'warning',
                showCancelButton: true,
                confirmButtonClass: \"btn btn-primary flat-buttons waves-effect waves-button\",
                cancelButtonClass: \"btn btn-danger flat-buttons waves-effect waves-button\",
                buttonsStyling: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Delete!'
               }).then(function () {
                    swal({
                        title: 'Delete Item !',
                        type: 'success',
                        text: 'Item Succesfully Delete!',
                        confirmButtonClass: \"btn btn-primary flat-buttons waves-effect waves-button\",
                        cancelButtonClass: \"btn btn-danger flat-buttons waves-effect waves-button\",
                        buttonsStyling: false
                    }).then(function() {
                        location.href = '?r=product/delete-cart&tmp='+id;
                    });
                }).catch(swal.noop)
            })
           
        })(jQuery);  

        ");        
?>