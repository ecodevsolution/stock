<?php
    $this->registerJs('
        $(document).ready(function(){
            var myVar;
            $( "#homeClick" ).click(function() {
                myFunction(this);
            });
             $( "#productClick" ).click(function() {
                myFunction(this);
            });
             $( "#listClick" ).click(function() {
                myFunction(this);
            });
             $( "#cart" ).click(function() {
                myFunction(this);
            });
             $( "#seller" ).click(function() {
                myFunction(this);
            });
             $( "#stockClick" ).click(function() {
                myFunction(this);
            });
            function myFunction(div) {
                $(".loader").toggle();
                $(div).toggle();

            }    
        });
    ');
?>

<a id="homeClick" href="<?= Yii::$app->homeUrl; ?>">
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
   
    $controller=Yii::$app->controller->action->id;

    if($controller == 'index' || $controller == 'list'){

    
?>
<a id="productClick" href="?r=product/index">
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="content">
            <div class="dashboard_v4_box_icon float-xs-left warning_box">
                <i class="fa fa-tag"></i>
            </div>
            <div class="dashboard_v4_box_title float-xs-right">
                <h4>Add Products</h4>                
            </div>
        </div>
    </div>
</a>


<a id="listClick" href="?r=product/list">
   <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
       <div class="content">
           <div class="dashboard_v4_box_icon float-xs-left success_box">
               <i class="fa fa-shopping-bag"></i>
           </div>
           <div class="dashboard_v4_box_title float-xs-right">
               <h4>List Products</h4>               
           </div>
       </div>
   </div>
</a>

<?php } ?>


<?php
    if($controller == 'seller'  || $controller == 'cart' || $controller == 'invoice'){    
?>

<a id="cart" href="?r=product/cart">
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="content">
            <div class="dashboard_v4_box_icon float-xs-left success_box">
                <i class="fa fa-cart-plus"></i>
            </div>
            <div class="dashboard_v4_box_title float-xs-right">
                <h4>Add to Cart</h4>                
            </div>
        </div>
    </div>
</a>
<a id="seller" href="?r=product/seller">
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="content">
            <div class="dashboard_v4_box_icon float-xs-left warning_box">
                <i class="fa fa-shopping-bag"></i>
            </div>
            <div class="dashboard_v4_box_title float-xs-right">
                <h4>Order History</h4>                
            </div>
        </div>
    </div>
</a>
<?php } ?>


<?php
    if($controller == 'stock' ){    
?>

<a id="stockClick" href="?r=product/stock">
     <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
         <div class="content">
             <div class="dashboard_v4_box_icon float-xs-left danger_box">
                 <i class="fa fa-book"></i>
             </div>
             <div class="dashboard_v4_box_title float-xs-right">
                 <h4>Out Of Stock</h4>                 
             </div>
         </div>
     </div>
 </a>
<?php } ?>