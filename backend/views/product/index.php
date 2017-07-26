<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    use common\models\Category;
    use common\models\Brand;
    use common\models\Tblsize;
    use wbraganca\dynamicform\DynamicFormWidget;
?>


<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="row">
        <div class="dashboard_v4_box_block">

           <?php
                include "menu.php";           
           ?>

        </div>
    </div>

    <div class="content">
        <div class="general-elements-content">
            <?php 
                $form = ActiveForm::begin([
						'options'=>[
									'data-style'=>'circle',
									'role'=>'form',
									'class'=>'wizard',
									'id' => 'dynamic-form']
									
									]); ?>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <div class="nav-tab-horizontal">

                            <div class="divider15"></div>
                            <h4 class="page-content-title">Add Product</h4>
                            <div class="tab-content">
                                <div class="tab-pane active btn-margin" id="text-fields" role="tabpanel">
                                    <div class="element-margin-bottom">
                                        <label>SKU</label>
                                         <?= $form->field($model, 'sku')->textInput(['maxlength' => true,'value'=>$sku,'readonly'=>true])->label(false); ?>
                                        
                                        <label>SKU QrCode</label>
                                        <div class="form-group">
                                            <img src="https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=<?= $sku ?>&choe=UTF-8" style="width:100px" title="<?= $sku ?>" />
                                        </div>

                                        <label>Product Name</label>
                                         <?= $form->field($model, 'product_name')->textInput(['maxlength' => true])->label(false); ?>

                                        <label>Category</label>
                                        <?= $form->field($model, 'idcategory')->dropDownList(
												ArrayHelper::map(Category::find()->all(),'idcategory', 'name_category'),
												['prompt'=>'- Choose -']
											)->label(false)
										?>
                                       
                                      

                                        <label>Brand</label>
                                        <?= $form->field($model, 'idbrand')->dropDownList(
												ArrayHelper::map(Brand::find()->all(),'idbrand', 'brand'),
												['prompt'=>'- Choose -']
											)->label(false)
										?>
                                       


                                        <label>Price</label>
                                        <?= $form->field($model, 'price')->textInput(['maxlength' => true])->label(false); ?>
                                        
                                        <label>Selling Price</label>
                                        <?= $form->field($model, 'price_sell')->textInput(['maxlength' => true])->label(false); ?>

                                        <!--IMAGE-->
                                        <label>Image</label>
                                        <div class="form-group">
                                            <div class="tab-content">
                                                <div class="tab-pane active btn-margin" id="maxsize-upload" role="tabpanel"> 
                                                  <?= $form->field($model, 'image')->fileInput(['class'=>'dropify','data-plugin'=>'dropify','data-height'=>'dropify','data-height'=>350,'data-max-file-size'=>'1M'])->label(false) ?>                            
                                                    
                                                </div>

                                            </div>
                                        </div>
                                        <!--END OF UPLOAD -->

                                        <div class="form-group">
                                            <?= Html::submitButton('Save', ['class' => 'btn-hover-animation hvr-float-shadow']) ?>                                            
                                        </div>
                                    </div>

                                
                                </div>
                                
                            </div>
                        </div>

                    </div>

                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12">

                        <fieldset>								
																	 
									<div class="panel-body">
										<?php DynamicFormWidget::begin([
											'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
											'widgetBody' => '.container-items', // required: css class selector
											'widgetItem' => '.item', // required: css class
											'limit' => 100, // the maximum times, an element can be cloned (default 999)
											'min' => 1, // 0 or 1 (default 1)
											'insertButton' => '.tambah-item', // css class
											'deleteButton' => '.hapus-item', // css class
											'model' => $modeldetail[0],
											'formId' => 'dynamic-form',
											'formFields' => [
												'color',
												'size',
                                                'stock',

											],
										]);											
										?>
					
										<div class="container-items"><!-- widgetContainer -->
											<?php foreach ($modeldetail as $i => $modeldetails): ?>
											<div class="item panel panel-default"><!-- widgetBody -->
												<div class="panel-heading">
													<h3 class="panel-title pull-left">Product Attribute</h3>
													<div class="pull-right">
														<button type="button" class="tambah-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
														<button type="button" class="hapus-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
													</div>
													<div class="clearfix"></div>
												</div>
												<div class="panel-body">
													<div class="row">
														
														
														<div class="col-md-12">
															<label>Color</label>
                                                            <input type="color" name="color[]" value="" class="form-control">                                                                                                                     
															
														</div>
														<div class="col-md-6">
															<label>Size</label>
                                                            <?= $form->field($modeldetails, "[{$i}]size")->dropDownList(
                                                                    ArrayHelper::map(Tblsize::find()->all(),'idsize', 'size'),
                                                                    ['prompt'=>'- Choose -']
                                                                )->label(false)
                                                            ?>															
														</div>
														<div class="col-md-6">
															<label>Stock</label>
															<?= $form->field($modeldetails, "[{$i}]stock")->textInput(['class'=>'form-control'])->label(false)?>
														</div>
													</div>
												</div><!-- .row -->
											</div>
										</div>
										<?php endforeach; ?>
										</div>
										<?php DynamicFormWidget::end(); ?>
									</div>
															
							</fieldset>										

                            
                        <!--<div class="nav-tab-horizontal">

                            <div class="divider15"></div>
                            <h4 class="page-content-title">Add Product Attribute</h4>
                            <div class="tab-content">
                                <div class="tab-pane active btn-margin" id="text-fields" role="tabpanel">
                                    <div class="element-margin-bottom">

                                        <label>Color</label>
                                        <div class="input-group form-group">
                                            <div class="input-group colorpicker-component colorpicker-element" data-plugin="colorpicker">
                                                <input type="text" value="#00AABB" class="form-control">
                                                <span class="input-group-addon"><i style="background-color: rgb(0, 170, 187);"></i></span>
                                            </div>
                                        </div>

                                        <label>Size</label>
                                        <div class="form-group">
                                            <select class="form-control" name="size">
                                            <option>1</option>
                                            <option>XL</option>
                                            <option>1</option>
                                            <option>1</option>
                                            <option>1</option>
                                        </select>
                                        </div>

                                        <label>Stock</label>
                                        <div class="form-group">
                                            <input class="form-control" type="text" name="name" value="">
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>-->

                               
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
