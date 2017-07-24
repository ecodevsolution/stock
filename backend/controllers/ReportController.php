<?php

namespace backend\controllers;

use common\models\Order;

class ReportController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = Order::find()
                ->OrderBy(['date'=>SORT_DESC])
                ->Limit(10)
                ->where(['<>','status',2])              
                ->All();

        
        return $this->render('index',[
            'model'=>$model,
        ]);        
    }
    public function actionGetdata()
    {
      
       $model = Order::find()
            ->Where(['between','date',$_POST['tgl_awal'], $_POST['tgl_akhir']])
            ->andWhere(['<>','status',2])
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
                    echo '<td>'.strtoupper($models->customer).'</td>';
                    echo '<td>'.$models->total_qty.'</td>';
                    echo '<td>'.number_format($models->grandtotal,0,".",".").'</td>';
                    echo '<td>'.date('d M Y',strtotime($models->date)).'</td>';
                    echo '<td>'.$pay.'</td>';
                    echo '<td>'.$status.'</td>';
                    
                echo '</tr>';        
            endforeach;
        }else{
            echo '<tr><td colspan="6">No data(s) found...</td></tr>';
        }
    }
        public function actionSell()
    {
        $model = Order::find()
                ->OrderBy(['date'=>SORT_DESC])
                ->Limit(10)
                ->where(['<>','status',2])              
                ->All();

        
        return $this->render('sell',[
            'model'=>$model,
        ]);    
    }
    public function actionLoss()
    {
         $model = Order::find()
                ->OrderBy(['date'=>SORT_DESC])
                ->Limit(10)
                ->where(['<>','status',2])              
                ->All();

        
        return $this->render('loss',[
            'model'=>$model,
        ]);    
    }
    public function actionProfit()
    {
         $model = Order::find()
                ->OrderBy(['date'=>SORT_DESC])
                ->Limit(10)
                ->where(['<>','status',2])              
                ->All();

        
        return $this->render('profit',[
            'model'=>$model,
        ]);    
    }

    

}
