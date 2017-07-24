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
        
        <div class="form-group">
            <input type="text" class="form-control forgot-form-border" name='username' required placeholder="Enter Username">
        </div>

         <?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-login', 'name' => 'login-button']) ?> 
        <div class="goto-login">
            <div class="forgot-login">
                <i class="arrow_back"></i>
                Go to <a href="?r=site/login">Login</a>
            </div>

        </div>
   <?php ActiveForm::end(); ?>
</div>