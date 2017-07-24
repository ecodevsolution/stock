<?php
    /* @var $this yii\web\View */
    /* @var $form yii\bootstrap\ActiveForm */
    /* @var $model \common\models\LoginForm */
    
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;
    
    $this->title = 'Login';
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="login-form">
    <?php $form = ActiveForm::begin([
        'id' => 'form-validation',
        'fieldConfig' => [
            'options' => [
                'tag' => false,
            ],
        ],
    ]); ?>    
        
        <?= $form->field($model, 'username')->textInput(['class'=>'form-control login-form-border','placeholder'=>'Enter Username','required'=>'required'])->label(false)?>
        <?= $form->field($model, 'password')->passwordInput(['class'=>'form-control login-form-border','placeholder'=>'Enter Password','required'=>'required'])->label(false) ?>                        
      
        <style>
            .help-block{
                color:#ff4a14;
            }
        </style>

         <?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-login', 'name' => 'login-button']) ?> 
        <div class="goto-login">
           
            <div class="forgot-password-login">
                <a href="?r=site/forgot-password">
                Forgot password?
                </a>
            </div>
        </div>
   <?php ActiveForm::end(); ?>
</div>