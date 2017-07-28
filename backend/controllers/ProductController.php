<?php

namespace backend\controllers;


use Yii;
use common\models\Product;
use common\models\ProductAttribute;
use common\models\Order;
use common\models\OrderDetail;
use common\models\TempOrderDetail;
use common\models\HistoryOrder;
use common\models\PurchaseOrder;
use yii\web\UploadedFile;
use common\models\Model;
use mPDF;


class ProductController extends \yii\web\Controller
{
    // ADD PRODUCT
    public function actionIndex()
    {
        $model = new Product();
        $modeldetail = [new ProductAttribute];
        

        $sku = mt_rand(100000,999999); 
        $sku = date('y').''.$sku;

        $modeldetail = Model::createMultiple(ProductAttribute::classname());
		Model::loadMultiple($modeldetail, Yii::$app->request->post());

        if ($model->load(Yii::$app->request->post())){

            $model->image=UploadedFile::getInstance($model,'image');
            $imageName = md5(uniqid($model->image));
			$model->image->saveAs('img/product/'.$imageName. '.'.$model->image->extension );
			$model->image= $imageName. '.'.$model->image->extension;
            $model->id = Yii::$app->user->identity->id;
            $model->date = date('Y-m-d');
			$model->save();

            foreach ($modeldetail as $key => $modeldetails) {
			  
                $modeldetails->sku = $model->sku;
				$modeldetails->color = $_POST['color'][$key];
                $modeldetails->save();
                
                $purchase = new PurchaseOrder();
                $purchase->sku = $model->sku;
                $purchase->id = Yii::$app->user->identity->id;
                $purchase->qty = $modeldetails->stock;
                $purchase->price = $model->price;
                $purchase->date =  $model->date;                
                $purchase->save();

			}
            return $this->redirect(['list']);

        }else{
            return $this->render('index',[
                'model'=>$model,
                'sku' => $sku,
                'modeldetail' => (empty($modeldetail)) ? [new ProductAttribute] : $modeldetail,
            ]);
        }
        
    }


    // ADD TEMP CART
    public function actionCart(){
        $model = new Order();
        $detail = new OrderDetail();
        $product = Product::find()
                  ->joinWith('category')
                  ->joinWith('brand')
                  ->All();
        $tmp = new TempOrderDetail();
        $modelOrder = TempOrderDetail::find()
                    ->joinWith('product')
                    ->all();
        
         $subTotal = 0;
         $disc = 0;
         $total_paid = 0;
        
         foreach($modelOrder as $modelOrders):
             $subTotal += $modelOrders->tmp_price * $modelOrders->tmp_qty;
             $disc += $modelOrders->tmp_discount;
             $total_paid += $modelOrders->tmp_total_price;             
         
         endforeach;

         

        if(isset($_POST['color']) && isset($_POST['qty'])){
            
            $price = Product::find()
                  ->where(['sku'=>$_POST['sku']])
                  ->One();
            
             $check = TempOrderDetail::find()                    
                    ->where(['tmp_sku'=>$_POST['sku']])
                    ->Andwhere(['tmp_size'=>$_POST['size']])
                    ->Andwhere(['tmp_color'=>$_POST['color']])                    
                    ->One();
            if($check){
                $check->tmp_qty = $check->tmp_qty + $_POST['qty'];
                $check->save();
                return $this->redirect(['cart']);  
            }else{
                        
                $tmp->tmp_sku = $_POST['sku'];
                $tmp->tmp_qty = $_POST['qty'];
                $tmp->tmp_price = $price->price_sell;

                $tmp->tmp_size = $_POST['size'];
                $tmp->tmp_color = $_POST['color'];
                $tmp->tmp_discount = $_POST['discount'];
                
                $total = $price->price_sell * $_POST['qty'];
                $discount = $_POST['discount'];
                $tmp->tmp_total_price = $total - $discount;
                $tmp->tmp_discount_amount = $discount;  

                $tmp->save();
            }
             return $this->redirect(['cart']);   
        }

        
        return $this->render('cart',[
            'model'=>$model,
            'product'=>$product,
            'detail' => $detail,
            'modelOrder'=>$modelOrder,
            'subTotal'=>$subTotal,
            'disc'=>$disc,
            'total_paid'=>$total_paid

        ]);
    }

    // AJAX SIZE
    public function actionItems($id){
        
        $attr = ProductAttribute::find()              
                ->joinWith('tblSize')
  
                ->Where(['sku'=>$id])
                ->andWhere(['<>','stock',0])     
                ->groupBy('size')
                ->all();
        $size = '';
        
            $size .= '<option value="">- Choose -</option>';
        foreach($attr as $attrs):       
            $size .= '<option value='.$attrs->size.'>'.$attrs->tblSize->size.'</option>';
        endforeach;      

       echo $size;

    

          
    }


    // AJAX COLOR 
     public function actionColor($size, $p){
        
        $attr = ProductAttribute::find()
                ->Where(['sku'=>$p])
                ->andWhere(['size'=>$size])
                ->andWhere(['<>','stock',0])   
                ->All();
        $color = '';
        
        $color .='<div class="form-group">';
        $color .='<label for="recipient-name" class="form-control-label">Color</label>';
        $color .= '<br/>';

         foreach($attr as $key =>  $attrs):   
             $color .='<div class="radio-inline" style="padding-left: 0px;">';
             $color .='<div class="radio-button">';

           
             $color .='<input value="'.$attrs->color.'" required id="radio-button'.$key.'" name="color" type="radio">';
             $color .='<label for="radio-button'.$key.'" style="background-color:'.$attrs->color.'"></label>';
             $color .='<span style="margin-left: 22px;background-color:'.$attrs->color.';width:50px"></span>';
             
             $color .='</div>';
        endforeach;

        $color .='</div>';
        $color .='</div>';

                    
       echo $color;      

          
    }


    // ADD PERMANENT ORDER
    public function actionInvoice($act = 0, $or = '',$c = '',$p= ''){
        

        $lookModel = Order::find()
                ->Where(['order_no'=>$or])
                ->One();
        
   
                    
        $lookModelDetail = OrderDetail::find()
                ->joinWith('product')
                ->Where(['order_no'=>$or])
                ->all();
       
         $total_paid = 0;
         foreach($lookModelDetail as $lookModelDetails):
             $total_paid += $lookModelDetails->total_price;
         endforeach;


        if ($act == 1){
        
            $model = new Order();
            $history = new HistoryOrder();
            $tmpModel = TempOrderDetail::find()
                        ->All();

            $inv = mt_rand(100000,999999); 
            $inv = 'INV-'.date('y').''.$inv;

            $subTotal = 0;
            $disc = 0;
            $total_paid = 0;
            $total_qty = 0;

            foreach($tmpModel as $tmpModels):

                 $stock = ProductAttribute::find()
                        ->where(['sku'=>$tmpModels->tmp_sku])
                        ->Andwhere(['color'=>$tmpModels->tmp_color])
                        ->Andwhere(['size'=>$tmpModels->tmp_size])
                        ->One();
                
                $stock->stock = $stock->stock - $tmpModels->tmp_qty;
                $stock->save();

                $subTotal += $tmpModels->tmp_price * $tmpModels->tmp_qty;
                $disc += $tmpModels->tmp_discount_amount;
                $total_paid += $tmpModels->tmp_total_price;
                $total_qty += $tmpModels->tmp_qty;
            endforeach;

            $model->order_no = $inv;
            $model->customer = $c;
            $model->payment = $p;
            $model->total_qty = $total_qty;
            $model->grandtotal = $total_paid;
            $model->date = date('Y-m-d');
            $model->status = 1;
            $model->save(false);

            $history->order_id = $model->order_no;
            $history->id = Yii::$app->user->identity->id;
            $history->status = 1;
            $history->date = date('Y-m-d');
            $history->save();
            
            foreach ($tmpModel as $tmpModels):
                $modelDetail = new OrderDetail();

                $modelDetail->order_no = $model->order_no;
                $modelDetail->sku = $tmpModels->tmp_sku;
                $modelDetail->qty = $tmpModels->tmp_qty;
                $modelDetail->price = $tmpModels->tmp_price;
                $modelDetail->size = $tmpModels->tmp_size;
                $modelDetail->color = $tmpModels->tmp_color;
                $modelDetail->discount = $tmpModels->tmp_discount;
                $modelDetail->total_price = $tmpModels->tmp_total_price;
                $modelDetail->save();                                              
            endforeach;

            foreach($tmpModel as $tmpModels):
              $tmpModels->delete();
            endforeach;

            return $this->redirect(['invoice','act'=>0,'or'=> $model->order_no]);
        }else{
            return $this->render('invoice',[
                'lookModelDetail'=>$lookModelDetail,
                'lookModel' =>$lookModel,
                'total_paid'=> $total_paid

            ]);
        }
    }

    // PRINT INVOICE
    public function actionPrint($id = 1, $or = ''){
		// $mpdf=new mPDF('utf-8', array(80,100));

        $model = Order::findOne($or);
        $detail = OrderDetail::find()
                ->joinWith('product')
                ->where(['order_no'=>$or])
                ->all();

        $data = '';
        foreach($detail as $details):
            $add = '';
            if($details->discount > 0){
                $add = '-';
            }
            
              $data .= ' <tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">';
                $data .= '<td style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; border-top-width: 1px; border-top-color: #eee; border-top-style: solid; margin: 0; padding: 5px 0;" valign="top">'.$details->product->product_name.'</td>';
                $data .= '<td style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; border-top-width: 1px; border-top-color: #eee; border-top-style: solid; margin: 0; padding: 5px 0;" valign="top">'.$details->qty.'</td>';
                $data .= '<td style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; border-top-width: 1px; border-top-color: #eee; border-top-style: solid; margin: 0; padding: 5px 0;" valign="top">'.$add.''.number_format($details->discount,0,".",".").'</td>';
                $data .= '<td class="alignright" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: right; border-top-width: 1px; border-top-color: #eee; border-top-style: solid; margin: 0; padding: 5px 0;" align="right" valign="top">'.number_format($details->total_price,0,".",".").'</td>';
            $data .= '</tr>';

          
        endforeach;

		$mpdf=new mPDF('utf-8');
        
        $mpdf->WriteHTML(' <html xmlns="http://www.w3.org/1999/xhtml" style="font-family: Helvetica Neue, Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
            <head>
                <meta name="viewport" content="width=device-width" />
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <title>Billing e.g. invoices and receipts</title>
                <style type="text/css">
                    img {
                    max-width: 100%;
                    }
                    body {
                    -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em;
                    }
                    body {
                    background-color: #f6f6f6;
                    }
                    @media only screen and (max-width: 640px) {
                    body {
                    padding: 0 !important;
                    }
                    h1 {
                    font-weight: 800 !important; margin: 20px 0 5px !important;
                    }
                    h2 {
                    font-weight: 800 !important; margin: 20px 0 5px !important;
                    }
                    h3 {
                    font-weight: 800 !important; margin: 20px 0 5px !important;
                    }
                    h4 {
                    font-weight: 800 !important; margin: 20px 0 5px !important;
                    }
                    h1 {
                    font-size: 22px !important;
                    }
                    h2 {
                    font-size: 18px !important;
                    }
                    h3 {
                    font-size: 16px !important;
                    }
                    .container {
                    padding: 0 !important; width: 100% !important;
                    }
                    .content {
                    padding: 0 !important;
                    }
                    .content-wrap {
                    padding: 10px !important;
                    }
                    .invoice {
                    width: 100% !important;
                    }
                    }
                </style>
            </head>
            <body itemscope itemtype="http://schema.org/EmailMessage" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">
                <table class="body-wrap" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">
                    <tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                        <td style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
                        <td class="container" width="600" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;" valign="top">
                            <div class="content" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
                                <table class="main" width="100%" cellpadding="0" cellspacing="0" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;" bgcolor="#fff">
                                    <tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <td class="content-wrap aligncenter" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 20px;" align="center" valign="top">
                                            <table width="100%" cellpadding="0" cellspacing="0" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                <tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                    <td class="content-block" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                                    <img src="https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl='.$model->order_no.'&choe=UTF-8" style="width:100px" title="'.$model->order_no.'>" />
                                                    </td>
                                                </tr>
                                                <tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                    <td class="content-block" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                                        <h2 class="aligncenter" style="font-family: Helvetica Neue,Helvetica,Arial,Lucida Grande,sans-serif; box-sizing: border-box; font-size: 24px; color: #000; line-height: 1.2em; font-weight: 400; text-align: center; margin: 40px 0 0;" align="center">Beebow Kids Fashion</h2>
                                                    </td>
                                                </tr>
                                                <tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                    <td class="content-block aligncenter" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top">
                                                        <table class="invoice" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; text-align: left; width: 80%; margin: 40px auto;">
                                                            <tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                                <td style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 5px 0;" valign="top">'.$model->customer.'<br style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;" />Invoice #'.$model->order_no.'<br style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;" />'.date('d M Y',strtotime($model->date)).'</td>
                                                            </tr>
                                                            <tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                                <td style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 5px 0;" valign="top">
                                                                    <table class="invoice-items" cellpadding="0" cellspacing="0" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; margin: 0;">
                                                                       '.$data.'
                                                                       
                                                                        <tr class="total" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                                            <td class="alignright" colspan="3" width="80%" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: right; border-top-width: 2px; border-top-color: #333; border-top-style: solid; border-bottom-color: #333; border-bottom-width: 2px; border-bottom-style: solid; font-weight: 700; margin: 0; padding: 5px 0;" align="right" valign="top">Total</td>
                                                                            <td class="alignright" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: right; border-top-width: 2px; border-top-color: #333; border-top-style: solid; border-bottom-color: #333; border-bottom-width: 2px; border-bottom-style: solid; font-weight: 700; margin: 0; padding: 5px 0;" align="right" valign="top">'.number_format($model->grandtotal,0,".",".").'</td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                    <td class="content-block aligncenter" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top">                                                
                                                    </td>
                                                </tr>
                                                <tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                    <td class="content-block aligncenter" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top">
                                                    '.strtoupper($model->payment).' <br/> THANK YOU
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            
                            </div>
                        </td>
                        <td style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
                    </tr>
                </table>
            </body>
        </html>
            
        ');
        // $mpdf->Output('MyPDF.pdf', 'D');
        $mpdf->Output();
        exit;

	}
    

    // EMAIL INVOICE ORDER
    public function actionEmail($e ='', $or ='')
    {

        $model = Order::findOne($or);
        $detail = OrderDetail::find()
                ->joinWith('product')
                ->where(['order_no'=>$or])
                ->all();

        $data = '';
        foreach($detail as $details):
            $add = '';
            if($details->discount > 0){
                $add = '-';
            }
            
              $data .= ' <tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">';
                $data .= '<td style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; border-top-width: 1px; border-top-color: #eee; border-top-style: solid; margin: 0; padding: 5px 0;" valign="top">'.$details->product->product_name.'</td>';
                $data .= '<td style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; border-top-width: 1px; border-top-color: #eee; border-top-style: solid; margin: 0; padding: 5px 0;" valign="top">'.$details->qty.'</td>';
                $data .= '<td style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; border-top-width: 1px; border-top-color: #eee; border-top-style: solid; margin: 0; padding: 5px 0;" valign="top">'.$add.''.number_format($details->discount,0,".",".").'</td>';
                $data .= '<td class="alignright" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: right; border-top-width: 1px; border-top-color: #eee; border-top-style: solid; margin: 0; padding: 5px 0;" align="right" valign="top">'.number_format($details->total_price,0,".",".").'</td>';
            $data .= '</tr>';

          
        endforeach;
        $from = array('automail@shoper.co.id' => 'Beebow Kids Fashion');
        Yii::$app->mailer->compose()
			->setFrom($from)
			->setTo($e)
            ->setBcc('ecodevsolution@gmail.com')
			->setSubject('Beebow | '.$or)
			->setHtmlBody('<html xmlns="http://www.w3.org/1999/xhtml" style="font-family: Helvetica Neue, Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
            <head>
                <meta name="viewport" content="width=device-width" />
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <title>Billing e.g. invoices and receipts</title>
                <style type="text/css">
                    img {
                    max-width: 100%;
                    }
                    body {
                    -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em;
                    }
                    body {
                    background-color: #f6f6f6;
                    }
                    @media only screen and (max-width: 640px) {
                    body {
                    padding: 0 !important;
                    }
                    h1 {
                    font-weight: 800 !important; margin: 20px 0 5px !important;
                    }
                    h2 {
                    font-weight: 800 !important; margin: 20px 0 5px !important;
                    }
                    h3 {
                    font-weight: 800 !important; margin: 20px 0 5px !important;
                    }
                    h4 {
                    font-weight: 800 !important; margin: 20px 0 5px !important;
                    }
                    h1 {
                    font-size: 22px !important;
                    }
                    h2 {
                    font-size: 18px !important;
                    }
                    h3 {
                    font-size: 16px !important;
                    }
                    .container {
                    padding: 0 !important; width: 100% !important;
                    }
                    .content {
                    padding: 0 !important;
                    }
                    .content-wrap {
                    padding: 10px !important;
                    }
                    .invoice {
                    width: 100% !important;
                    }
                    }
                </style>
            </head>
            <body itemscope itemtype="http://schema.org/EmailMessage" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">
                <table class="body-wrap" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">
                    <tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                        <td style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
                        <td class="container" width="600" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;" valign="top">
                            <div class="content" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
                                <table class="main" width="100%" cellpadding="0" cellspacing="0" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;" bgcolor="#fff">
                                    <tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <td class="content-wrap aligncenter" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 20px;" align="center" valign="top">
                                            <table width="100%" cellpadding="0" cellspacing="0" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                <tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                    <td class="content-block" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                                    <img src="https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl='.$model->order_no.'&choe=UTF-8" style="width:100px" title="'.$model->order_no.'>" />
                                                    </td>
                                                </tr>
                                                <tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                    <td class="content-block" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                                        <h2 class="aligncenter" style="font-family: Helvetica Neue,Helvetica,Arial,Lucida Grande,sans-serif; box-sizing: border-box; font-size: 24px; color: #000; line-height: 1.2em; font-weight: 400; text-align: center; margin: 40px 0 0;" align="center">Beebow Kids Fashion</h2>
                                                    </td>
                                                </tr>
                                                <tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                    <td class="content-block aligncenter" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top">
                                                        <table class="invoice" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; text-align: left; width: 80%; margin: 40px auto;">
                                                            <tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                                <td style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 5px 0;" valign="top">'.$model->customer.'<br style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;" />Invoice #'.$model->order_no.'<br style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;" />'.date('d M Y',strtotime($model->date)).'</td>
                                                            </tr>
                                                            <tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                                <td style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 5px 0;" valign="top">
                                                                    <table class="invoice-items" cellpadding="0" cellspacing="0" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; margin: 0;">
                                                                       '.$data.'
                                                                       
                                                                        <tr class="total" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                                            <td class="alignright" colspan="3" width="80%" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: right; border-top-width: 2px; border-top-color: #333; border-top-style: solid; border-bottom-color: #333; border-bottom-width: 2px; border-bottom-style: solid; font-weight: 700; margin: 0; padding: 5px 0;" align="right" valign="top">Total</td>
                                                                            <td class="alignright" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: right; border-top-width: 2px; border-top-color: #333; border-top-style: solid; border-bottom-color: #333; border-bottom-width: 2px; border-bottom-style: solid; font-weight: 700; margin: 0; padding: 5px 0;" align="right" valign="top">'.number_format($model->grandtotal,0,".",".").'</td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                    <td class="content-block aligncenter" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top">                                                
                                                    </td>
                                                </tr>
                                                <tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                    <td class="content-block aligncenter" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top">
                                                    '.strtoupper($model->payment).' <br/> THANK YOU
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            
                            </div>
                        </td>
                        <td style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
                    </tr>
                </table>
            </body>
        </html>')
			->send();

        return $this->redirect(['invoice','act'=>0,'or'=> $or]);
        
    }

    public function actionSeller()
    {
        $model = Order::find()
                ->OrderBy(['date'=>SORT_DESC])
                ->Limit(10)               
                ->All();
        return $this->render('seller',[
            'model'=>$model,
        ]);
    }

    
    public function actionGetdata()
    {
      
       $model = Order::find()
            ->Where(['between','date',$_POST['tgl_awal'], $_POST['tgl_akhir']])
            ->andWhere(['payment'=>$_POST['payment']])
            ->all();
        
        if(!empty($model)){
            foreach($model as $models):                  
                if($models->status == 1){
                    $status = "<span class='tag square-tag tag-success'>Success</span>";
                }else{
                    $status = "<span class='tag square-tag tag-danger'>Void</span>";
                }

                if($models->payment == 'Credit'){
                    $pay = "<span class='tag square-tag tag-warning'>Credit</span>";
                }else{
                    $pay = "<span class='tag square-tag tag-success'>Cash/Debit</span>";
                }

                echo '<tr>';
                    echo '<td><a href="?r=product/invoice&act=0&or='.$models->order_no.'">'.$models->order_no.'</a></td>';
                    echo '<td>'.ucwords($models->customer).'</td>';
                    echo '<td>'.$models->total_qty.'</td>';
                    echo '<td>'.number_format($models->grandtotal,0,".",".").'</td>';
                    echo '<td>'.date('d M Y',strtotime($models->date)).'</td>';
                    echo '<td>'.$pay.'</td>';
                    echo '<td>'.$status.'</td>';
                    echo '<td><a href="#" title="void transaction"><span class="basic_table_icon"><i class="icon icon_trash"></i></span></a></td>';
                echo '</tr>';        
            endforeach;
        }else{
            echo '<tr><td colspan="6">No data(s) found...</td></tr>';
        }
    }
   
    public function actionStock()
    {
       $connection = \Yii::$app->db;
       $sql = $connection->createCommand("SELECT * FROM product_attribute a JOIN product b ON a.sku = b.sku JOIN category c ON b.idcategory = c.idcategory JOIN brand d ON b.idbrand = d.idbrand WHERE stock < 2");
       $model = $sql->queryAll();

        return $this->render('stock',[            
            'model'=>$model
        ]);
    }

    public function actionList()
    {          
    
       if(isset($_POST['stock'])){
            $models = ProductAttribute::find()
                    ->where(['sku'=>$_POST['sku']])
                    ->Andwhere(['color'=>$_POST['color']])
                    ->Andwhere(['size'=>$_POST['size']])
                    ->one();
            $models->stock = $models->stock + $_POST['stock'];
            $models->save();

            $model = Product::findOne($models->sku);

             $purchase = new PurchaseOrder();
             $purchase->sku = $models->sku;
             $purchase->id = Yii::$app->user->identity->id;
             $purchase->qty = $_POST['stock'];
             $purchase->price = $model->price;
             $purchase->date =  date('Y-m-d');                
             $purchase->save();

            return $this->redirect(['list']);
       }
       $connection = \Yii::$app->db;
       $sql = $connection->createCommand("SELECT *, e.size sz FROM product_attribute a JOIN product b ON a.sku = b.sku JOIN category c ON b.idcategory = c.idcategory JOIN brand d ON b.idbrand = d.idbrand JOIN tblsize e ON e.idsize = a.size");
       $model = $sql->queryAll();

       return $this->render('list',[            
            'model' => $model
        ]);
    }

    public function actionTransaction($or){
        $model = Order::findOne($or);
        $history = new HistoryOrder();

        $model->status = 2;
        $model->save(false);

        $history->order_id = $model->order_no;
        $history->id = Yii::$app->user->identity->id;
        $history->status = 2;
        $history->date = date('Y-m-d');
        $history->save();


        $modelDetail = OrderDetail::find()
                    ->Where(['order_no'=>$or])
                    ->all();

        foreach($modelDetail as $modelDetails):
            $stock = ProductAttribute::find()
                    ->where(['sku'=>$modelDetails->sku])
                    ->Andwhere(['color'=>$modelDetails->color])
                    ->Andwhere(['size'=>$modelDetails->size])
                    ->One();
            
            $stock->stock = $stock->stock + $modelDetails->qty;
            $stock->save();
        endforeach;

        return $this->redirect(['seller']);
    }

    public function actionDeleteCart($tmp){
        $model = TempOrderDetail::findOne($tmp);
        $model->delete();
        return $this->redirect(['cart']);
    }

}
