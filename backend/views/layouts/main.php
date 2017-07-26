<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);

if (Yii::$app->controller->action->id === 'login' || Yii::$app->controller->action->id === 'forgot-password') { 
/**
 * Do not use this code in your template. Remove it. 
 * Instead, use the code  $this->layout = '//main-login'; in your controller.
 */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {

$this->registerJs('
    $(window).load(function() {
        $(".loader").fadeOut("slow");
    })
');



$this->registerCss("
.loader {
  position:absolute;
  top:0px;
  right:0px;
  width:100%;
  height:100%;
  background-color: rgba(8, 115, 128, 0.3);
  background-image:url('components/image/Loader.gif');
  background-size: 100px 100px;
  background-repeat:no-repeat;
  background-position:center;
  z-index:10000000;
  
  filter: alpha(opacity=40); /* For IE8 and earlier */
}
");

?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">

    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
            <title>
                <?= Html::encode($this->title) ?>
            </title>
            <?php $this->head() ?>
    </head>

    <body>
        <?php $this->beginBody() ?>
        <div class="loader-overlay">
            <div class="loader-preview-area">
                <div class="spinners">
                    <div class="loader">
                        <div class="rotating-plane"></div>
                    </div>
                </div>
            </div>
        </div>


        <div class="wrapper">
            <header id="header">
                <div class="header-width">
                    <div class="col-xl-9">
                        <div class="logo float-xs-left">
                            <a href="<?= Yii::$app->homeUrl ?>"><img src="components/image/web-logo.png" alt="logo"></a>
                        </div>
                    </div>
                    <div class="col-xl-3 header-right">
                        <div class="header-inner-right">
                            <div class="float-default searchbox">
                                <div class="right-icon">
                                    <a href="javascript:void(0)">
                                        <i class="icon_search"></i>
                                    </a>
                                </div>
                            </div>
                            
                          
                            <div class="float-default chat">
                                <div class="right-icon">
                                    <a href="#" data-plugin="fullscreen">
                                        <i class="arrow_expand"></i>
                                    </a>
                                </div>
                            </div>
							
							 <div class="float-default chat">
                                <div class="right-icon">
                                    <a href="javascript:void(0)" data-toggle="dropdown" data-open="true" data-animation="slideOutUp" aria-expanded="false">
                                    <img src="components/image/avatar5_big.png" alt="<?= Yii::$app->user->identity->username; ?>" />                                    

                                    </a>
                                    <ul class="dropdown-menu userChat" data-plugin="custom-scroll" data-height="77">
                                        <li>
                                             <?= Html::a(
													' 
                                                <div class="media">
                                                    <div class="media-left float-xs-left">
                                                       <img src="components/image/avatar5_big.png" alt="<?= Yii::$app->user->identity->username; ?>" />
                                                    </div>
                                                    <div class="media-body">
                                                         <h5>'.Yii::$app->user->identity->username.'</h5>
														 <p>'.Yii::$app->user->identity->email.'</p>
                                                        <div class="meta-tag text-nowrap">
                                                           <i class="fa fa-sign-out">Logout</i>
                                                        </div>
                                                        <div class="status online"></div>
                                                    </div>
                                                </div>',
												
													['/site/logout'],
													['data-method' => 'post']
												) ?>                                            
                                        </li>

                                    </ul>
                                </div>
                            </div>
							
							
							
                            <!--<div class="user-dropdown">
                                <div class="btn-group">
                                    <div class="user-header dropdown-toggle" data-toggle="dropdown" data-animation="slideOutUp" aria-haspopup="true" aria-expanded="false">
                                        <img src="components/image/avatar5_big.png" alt="<?= Yii::$app->user->identity->username; ?>" />
                                    </div>
                                    <div class="dropdown-menu drop-profile">
                                        <div class="userProfile">
                                            <img src="components/image/avatar5_big.png" alt="<?= Yii::$app->user->identity->username; ?>" />
                                            <h5><?= Yii::$app->user->identity->username; ?></h5>
                                            <p><?= Yii::$app->user->identity->email; ?></p>
                                        </div>
                                        <div class="dropdown-divider"></div>
                                        <?= Html::a(
                                            ' Logout',
                                            ['/site/logout'],
                                            ['data-method' => 'post', 'role'=>'button' ,'class' => 'btn btn-primary float-xs-right right-spacing']
                                        ) ?>

                                    </div>
                                </div>
                            </div>-->
                        </div>
                    </div>
                </div>
               
            </header>

            <div class="search-overlay">
                <div class="float-default search">
                    <div class="search_bg"></div>
                    <div class="right-icon search_box">
                        <div class="search-div">
                            <input type="text" class="search_input">
                            <label class="search-input-label">
                                <span class="input-label-title">Search</span>
                            </label>
                        </div>
                        <div class="divider50"></div>
                        <div class="search-result">
                            <div class="search-item">
                                <div class="search-image float-xs-left">
                                    <img src="components/image/guitar.jpg" alt="search-image">
                                </div>
                                <div class="search-info float-xs-right">
                                    <h3 class="title">Search here</h3>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse faucibus diam quis arcu lobortis sagittis. Etiam eu ornare mi. Interdum et malesuada fames ac ante ipsum primis in faucibus.
                                    </p>
                                </div>
                            </div>
                            <div class="divider15"></div>
                            <div class="search-item">
                                <div class="search-info search-full float-xs-right">
                                    <h3 class="title">Admin templates</h3>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse faucibus diam quis arcu lobortis sagittis. Etiam eu ornare mi. Interdum et malesuada fames ac ante ipsum primis in faucibus.
                                    </p>
                                </div>
                            </div>
                            <div class="divider15"></div>
                            <div class="search-item">
                                <div class="search-image float-xs-left">
                                    <img src="components/image/book-flower.jpg" alt="search-image">
                                </div>
                                <div class="search-info float-xs-right">
                                    <h3 class="title">Books</h3>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse faucibus diam quis arcu lobortis sagittis. Etiam eu ornare mi. Interdum et malesuada fames ac ante ipsum primis in faucibus.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="search_close icon_close"></div>
                </div>
            </div>

            <section id="main" class="container-fluid">
                <div class="row">
                    <section id="content-wrapper" class="form-elements">
                        <div class="loader"></div>
                        <div class="contain-inner dashboard_v4-page">
                            <?= $content ?>
                        </div>
                    </section>
                </div>
            </section>


            <footer id="footer">
                Copyright&copy; 2017, All Rights Reserved.
            </footer>

        </div>
        <?php $this->endBody() ?>
    </body>

    </html>
    <?php $this->endPage() ?>
<?php } ?>