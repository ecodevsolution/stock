<?php
use backend\assets\AppAsset;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
	<!DOCTYPE html>
	<html lang="<?= Yii::$app->language ?>">
		<head>
			<meta charset="<?= Yii::$app->charset ?>"/>
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<?= Html::csrfMetaTags() ?>
			<title>Login</title>
			<?php $this->head() ?>
		</head>
		
		<body>
			<?php $this->beginBody() ?>
            <div class="login-background">
                <div class="login-page">
                    <div class="main-login-contain">
                        <div class="login-circul text-xs-center">
                            <i class="icon_lock_alt login-icon-circul"></i>
                        </div>
				        <?= $content ?>
                    </div>
                </div>
            </div>
			<?php $this->endBody() ?>
		</body>
	</html>
<?php $this->endPage() ?>
