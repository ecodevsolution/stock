<?php
    use yii\helpers\Html;
    use yii\web\View;

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
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="dashboard_v4_box_block">

                <?php
                include "menu.php";

                 $root = '@web';


                 $this->registerJsFile("https://code.jquery.com/ui/1.12.1/jquery-ui.js",
                 ['depends' => [\yii\web\JqueryAsset::className()],
                 'position' => View::POS_HEAD]);
                  $this->registerJsFile($root."/components/plugins/sweetalert2/dist/sweetalert2.min.js",
                 ['depends' => [\yii\web\JqueryAsset::className()],
                 'position' => View::POS_END]);
                 $this->registerJsFile($root."/components/js/global/sweetalert.js",
                 ['depends' => [\yii\web\JqueryAsset::className()],
                 'position' => View::POS_END]);

           ?>

            </div>
        </div>

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
                    url: '?r=product/getdata',
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
        <div class="content" >
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row">

                    <div class="divider15 divider-lg-spacing"></div>
                    <div class="widget-primary alert-left-border-icon">
                        <div class="widget-content alert alert-primary m-0" role="alert">
                            <div class="alert-media-right" style="display: inline-block;" >
                                <p>
                                    Date Range : <input type="text"  required id="datepicker1"> To <input type="text" required id="datepicker2">
                                                 <select name="payment" class="form-control" id="payment" <select name="payment" class="form-control" id="payment" style="display: inline;width: 100px;">
                                                    <option value="cash"> Cash </option>
                                                    <option value="credit"> Credit </option>
                                                 </select>
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
                <h4 class="page-content-title">Order History</h4>
                <p>Top 10 Order History by Transaction Date.</p>
                <div class="basic_bootstrap_tbl bootstrap_tbl_no_border">
                    <div class="basic_table table-responsive">
                     <div class="loading-overlay" style="display: none;"><div class="overlay-content">Loading.....</div></div>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order NO</th>
                                    <th>Customer </th>
                                    <th>Total Qty</th>
                                    <th>GrandTotal</th>
                                    <th>Date</th>
                                    <th>Payment Method</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="userData">
                                <?php
                                    foreach($model as $models):
                                ?>
                                    <tr>
                                        <td><a href="?r=product/invoice&act=0&or=<?= $models->order_no ?>"><?= $models->order_no ?></a></td>
                                       <td>
                                            <?= ucwords($models->customer) ?>
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
                                        <td>
                                            <?php
                                                if($models->status == 1){        ?>                           
                                                    <a href="#" class="button" title="void transaction" data-id="<?= $models->order_no ?>"><span class="basic_table_icon  warning confirm"><i class="icon icon_trash"></i></span></a>
                                            <?php  } else{ echo"<span class='fa fa-times'></span>"; } ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php

$this->registerJs("
        (function($) {

           $(document).on('click', '.button', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            swal({
                title: 'Are you sure to void ?',
                text: 'You won\'t be able to revert this!',
                type: 'warning',
                showCancelButton: true,
                confirmButtonClass: \"btn btn-primary flat-buttons waves-effect waves-button\",
                cancelButtonClass: \"btn btn-danger flat-buttons waves-effect waves-button\",
                buttonsStyling: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Void!'
               }).then(function () {
                    swal({
                        title: 'Void Transaction!',
                        type: 'success',
                        text: 'Void Transaction Succesfully!',
                        confirmButtonClass: \"btn btn-primary flat-buttons waves-effect waves-button\",
                        cancelButtonClass: \"btn btn-danger flat-buttons waves-effect waves-button\",
                        buttonsStyling: false
                    }).then(function() {
                        location.href = '?r=product/transaction&or='+id;
                    });
                }).catch(swal.noop)
            })
           
        })(jQuery);  

        ");        
?>