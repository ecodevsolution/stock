<?php
    use yii\helpers\Html;
    use yii\web\View;
?>
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="row">
        <div class="dashboard_v4_box_block">
            <?php
                include "menu.php";
                
                $root = '@web';
                
                
            
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
        <section id="content-wrapper">
            
            <div class="content" id="invoice-page">
                <h4 class="page-content-title float-xs-left">Invoice</h4>
                <div class="divider15"></div>
                <div class="order-detail">
                   
                    <div class="col-lg-3 invoice-top-box">
                        <h6>Order:</h6>
                        <address class="address-detail">
                            <strong>Order#<?= $lookModel->order_no ?></strong>
                            <input type="hidden" value="<?= $lookModel->order_no ?>" name="order" id="order" />
                            <br>
                            To : <?= ucwords($lookModel->customer) ?>
                            <br>
                            Invoice Date : <?= date('d M Y', strtotime($lookModel->date)) ?>
                            <br>
                            Due Date : <?= date('d M Y', strtotime($lookModel->date)) ?>
                        </address>
                    </div>
                </div>
                <div class="divider15"></div>
                <div class="table-responsive table-striped basic_table">
                    <table class="table table-bordered table-grid-options">
                        <thead>
                            <tr>
                               <th>Product</th>
                               <th>Price</th>
                               <th>Quantity</th>
                               <th>Tax</th>
                               <th>Discount</th>
                               <th>Total price</th>
                            </tr>
                        </thead>
                        <tbody>
                             <?php
                                    foreach($lookModelDetail as $lookModelDetails):
                                ?>
                                <tr>
                                    <td><?= $lookModelDetails->product->product_name; ?></td>
                                    <td><?= number_format($lookModelDetails->price,0,".","."); ?></td>
                                    <td><?= $lookModelDetails->qty; ?></td>
                                    <td>0%</td>
                                    <td><?= $lookModelDetails->discount; ?></td>
                                    <td><?= number_format($lookModelDetails->total_price,0,".","."); ?></td>
  
                                </tr>

                                <?php
                                    endforeach;
                                ?>
                           
                            <tr>
                                <td colspan="2"></td>
                                <td colspan="2"></td>
                                <td class="text-xs-right"><strong class="invoice-total">Subtotal</strong></td>
                                <td><strong><?= number_format($total_paid,0,".",".") ?></strong></td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <td colspan="2"></td>
                                <td class="text-xs-right"><strong class="invoice-total">VAT</strong></td>
                                <td><strong>0</strong></td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <td colspan="2"></td>
                                <td class="text-xs-right"><strong class="invoice-total">Total</strong></td>
                                <td><strong><?= number_format($total_paid,0,".",".") ?></strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="divider15"></div>
                <div class="">
                    <h6>Thank you for business with us!!</h6>
                    <p>
                        All Price in Indonesian Rupiah (IDR)
                    </p>
                    <div class="row">
                        <div class="col-md-12 btn-invoice">
                            <a href="?r=product/print&id=1&or=<?= $lookModel->order_no ?>" class="btn btn-success invoice-print"><i aria-hidden="true" data-icon=""></i>&nbsp;&nbsp;Print</a>
                            <button class="btn btn-primary ajax-request"><i aria-hidden="true" class="fa fa-envelope-o"></i>Send Email</button>                            
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</section>

<?php

$this->registerJs("
        (function($) {

            $('.ajax-request').on('click', function () {
                var order = document.getElementById('order').value;   
                swal({
                    title: 'Please insert your email !',
                    input: 'email',
                    showCancelButton: true,
                    confirmButtonText: 'Send',
                    width: 600,
                    confirmButtonClass: 'btn btn-primary flat-buttons waves-effect waves-button',
                    cancelButtonClass: 'btn btn-danger flat-buttons waves-effect waves-button',
                    buttonsStyling: false,
                    showLoaderOnConfirm: true,
                    preConfirm: function (email) {
                        return new Promise(function (resolve, reject) {
                            setTimeout(function () {
                                
                                if (email === 'taken@example.com') {
                                    reject('This email is already taken.')
                                } else {
                                    resolve()
                                }
                            }, 2000)
                        })
                    },
                    allowOutsideClick: false
                }).then(function (email) {
                    swal({
                        type: 'success',
                        title: 'Wait until page reload!',
                        html: 'Submitted email: ' + '<strong>' + email + '</strong>',
                        confirmButtonClass: 'btn btn-primary flat-buttons waves-effect waves-button',
                        cancelButtonClass: 'btn btn-danger flat-buttons waves-effect waves-button',
                        buttonsStyling: false
                    }).then(function() {
                        location.href = '?r=product/email&e='+email+'&or='+order;
                    });
                }).catch(swal.noop)
            })
           
        })(jQuery);  

        ");        

?>