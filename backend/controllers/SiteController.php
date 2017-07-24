<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\Category;
use common\models\ProductAttribute;
use common\models\Order;


/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error','forgot-password'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $category = Category::find()
                    ->count();
        $product = ProductAttribute::find()
                    ->count();
        $order = Order::find()
                    ->where(['<>','status',2])
                    ->count();
        $oos = ProductAttribute::find()
                    ->where(['<','stock',2])
                    ->count();

        $model = new Category();
        $lookUp = Category::find()
                ->orderBy(['idcategory'=>SORT_DESC])
                ->all();

        if ($model->load(Yii::$app->request->post())){
            
            $model->category_color = $_POST['color'];
            $model->save();
            Yii::$app->session->setFlash('success', 'New Category Saved !.');
             return $this->redirect(['index']);            
        } else {
            return $this->render('index', [
                'model' => $model,
                'lookUp' => $lookUp,
                'category'=> $category,
                'product'=> $product,
                'order'=> $order,
                'oos'=> $oos
            ]);
        }
        
    }
  

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */

    public function actionForgotPassword(){
       if(isset($_POST['email'])){
            
       }
       return $this->render('forgot');
    }
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
