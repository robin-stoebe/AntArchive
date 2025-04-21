<?php

namespace app\controllers;

use Yii;
use app\models\Log;
use app\models\Lookup;
use app\models\LookupType;
use app\models\LookupTypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\AccessRule;
use app\components\Debug;
use app\components\Logout;


use yii\filters\AccessControl;

/**
 * LookupTypeController implements the CRUD actions for LookupType model.
 */
class LookupTypeController extends Controller
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
               'only' => ['index','create', 'update', 'delete','admin','list','blank'],
               'rules' => [
                   [
                       'actions' => ['index','create','update','delete','admin','list','blank'],
                       'allow' => true,
                       // Allow users, moderators and admins to create
                       'roles' => ['ADMIN'],

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
     * Lists all LookupType models.
     * @return mixed
     */
    public function actionIndex()
    {
        
        $searchModel = new LookupTypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LookupType model.
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
     * Creates a new LookupType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LookupType();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->lookup_type_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing LookupType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->lookup_type_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing LookupType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete()
    {
        $id=$_POST['id'];
        $model=$this->findModel($id);
        $info = $model->getAttributes();
        $model->delete();
        $log = new \app\models\Log;
        $log->entry('delete lookup',print_r($info,true));

        if(!Yii::$app->request->isAjax)
            return $this->redirect(['index']);
    }

     /**
     * Deletes an existing LookupType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionList($id){
        
        
        $model = $this->findModel($id);
        $log = new \app\models\Log;
        if(!empty($_POST)){
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                foreach($_POST['items'] as $lid => $item){
                    if(!empty($lid) and substr($lid,0,3)!='new' )
                        $lookup=Lookup::find()->where("lookup_id=$lid")->one();
                    else {
                        $lookup=new Lookup;
                        $lookup->type_id = $item['type_id'];
                    }
    //                print_r(['item'=>$item,$lookup->getAttributes()]);exit;
                    if(!empty($item['description'])){
                        if(empty($lookup->lookup_id) or $lookup->description!=$item['description'] or $lookup->weight!=$item['weight']){
                            $lookup->description=$item['description'];
                            $lookup->weight = $item['weight'];
                            $lookup->save();
                        }
                    } else {
                        if($lookup->lookup_id != null)
                            $lookup->delete();
                    }

                }
                
                $log->entry('updated Lookup', print_r([$model->getAttributes(),$_POST['items']],true));
            }
        }
        
        $new = new Lookup;
        $new->type_id=$id;
        $blankLine = $this->renderPartial('item',['model'=>$new]);
        $items = Lookup::find()->where("type_id=$id")->orderBy(["weight"=>$model->sort_direction])->all();
        return $this->render('items', [
            'model' => $model,
            'items' => $items,
            'blankLine'=>$blankLine
        ]);
    }
    
    public function actionBlank(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $id=$_REQUEST['id'];
        $newID=$_REQUEST['new'];
        $new = new Lookup;
        $new->lookup_id="new-".$newID;
        $new->type_id=$id;
        $blankLine=$this->renderPartial('item',['model'=>$new,'newID'=>$newID]);
        return  array('html'=>$blankLine);
    }
    
    
    
    
    /**
     * Finds the LookupType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LookupType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LookupType::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
