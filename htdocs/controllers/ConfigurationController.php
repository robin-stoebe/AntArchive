<?php

namespace app\controllers;

use Yii;
use app\models\Configuration;
use app\components\Debug;
use app\components\Logout;

use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\components\AccessRule;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

/**
 * ConfigurationController implements the CRUD actions for Configuration model.
 */
class ConfigurationController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
               'class' => AccessControl::className(),
               // We will override the default rule config with the new AccessRule class
               'ruleConfig' => [
                   'class' => AccessRule::className(),
               ],
               'only' => ['index','create', 'update', 'delete'],
               'rules' => [
                       [
                           'actions' => ['index','create','update','delete'],
                           'allow' => true,
                           // Allow users, moderators and admins to create
                           'roles' => ['ADMIN','ANALYST'],

                       ],
                   ],
                ],            
        ];
    }
    public function beforeAction($action){
            
        Logout::checkTime();
        return parent::beforeAction($action);
    }         
            
     
    /**
     * Lists all Configuration models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Configuration::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Configuration model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Configuration model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Configuration();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Configuration model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate()
    {
        global $debug;
        
        $model = new Configuration;

        if (!empty($_POST['item'])) {
            foreach($_POST['item'] as $item=>$value){
                Debug::debug($value,$item);
                if(!$modelItem= Configuration::find()->where(['item'=>$item])->one()){
                    $modelItem =  new Configuration;
                    $modelItem->item=$item;
                    
                }
                if(!empty($value))
                    $modelItem->value=$value;
                else 
                    $modelItem->value=NULL;
                if(!$modelItem->save()){
                    print_r($modelItem->getErrors());
                }
            }
            
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Configuration model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Configuration model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Configuration the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Configuration::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
