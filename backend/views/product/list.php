<?php
    use yii\helpers\Html;
    use yii\web\View;
   use yii\widgets\ActiveForm;
    
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
    <div class="content">
        <div class="row">
            <script>

                function downloadCSV(csv, filename) {
                    var csvFile;
                    var downloadLink;
                    filename = 'ListProduct <?= date('Y-m-d')?>.csv';
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

             <a href="#" onclick="exportTableToCSV('ListProduct.csv')"><span style="float:right;margin-right:10px" class="fa fa-file-excel-o fa-2x"></span></a>
            <div class="col-md-12">
            
                <div class="modal-body table-responsive">                
                    <table data-plugin="datatable" data-responsive="true" class="table table-striped table-hover  dt-responsive">
                        <thead>
                            <tr>
                                <th>SKU</th>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Brand</th>
                                <th>Color</th>
                                <th>Size</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Price</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody>                            
                            <?php foreach($model as $models): ?>
                            <tr onclick="document.location = '#';" data-id="<?= $models['sku'].";".$models['color'].";".$models['idsz']; ?>" data-target="#ModalProduct" class="open-AddBookDialog" data-toggle="modal">
                                <td>
                                    <?= $models['sku']; ?>
                                </td>
                                <td>
                                    <?= $models['product_name']; ?>
                                </td>
                                <td>
                                    <?= $models['name_category']; ?>
                                </td>
                                <td>
                                    <?= $models['brand']; ?>
                                </td>
                                <td>
                                   <span style="background-color:<?= $models['color']; ?>;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> 
                                </td>
                                <td>
                                    <?= $models['sz'];  ?>
                                </td>
                                <td>
                                    <?= $models['stock']; ?>
                                </td>
                                <td>
                                    <?php
                                        if($models['stock'] <= 2){
                                            echo "<span class='tag square-tag tag-danger'>out of Stock</span>";
                                        }else{
                                            echo "<span class='tag square-tag tag-success'>in Stock</span>";
                                        }
                                    ?>                                    
                                </td>
                                <td>
                                    <?= number_format($models['price_sell'],0,".",".") ?>
                                </td>
                                <td>                                    
                                      <a href="#" data-id="<?= $models['sku'];?>"  data-target="#modalTable" data-toggle="modal" title="void transaction"><span class="basic_table_icon"><i class="icon icon_pencil"></i></span></a>
                                </td>
                            </tr>                            
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>



    <!-- BEGIN MODAL -->
    <div class="modal fade" id="modalTable" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title text-primary">Add Stock</h4>
                </div>
                <div class="modal-body">
                   <?php $form = ActiveForm::begin(); ?>
                        <div class="form-group">
                            <label for="recipient-name" class="form-control-label">SKU</label>
                            <input type="text" id="sku" class="form-control" readonly=true name="sku">
                            <input type="hidden" id="color" class="form-control" readonly=true name="color">
                            <input type="hidden" id="size" class="form-control" readonly=true name="size">
                        </div>
                         <div class="form-group">
                            <label for="recipient-name" class="form-control-label">Stock</label>
                            <input type="text" class="form-control" required="required"  name="stock">
                        </div>
                         <?= Html::submitButton('Update', ['class' => 'btn btn-primary','style'=>'float:right']) ?>                        
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
            var res = myBookId.split(";");            
            $(".modal-body #sku").val( res[0] );
            $(".modal-body #color").val( res[1] );
            $(".modal-body #size").val( res[2] );
        });
    ');
?>