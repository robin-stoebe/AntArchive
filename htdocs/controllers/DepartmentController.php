<?php

namespace app\controllers;

use Yii;
use app\models\Department;
use app\models\Log;
use app\components\Debug;
use app\components\Logout;

use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;
use app\components\AccessRule;
use yii\filters\AccessControl;
/**
 * DepartmentController implements the CRUD actions for Department model.
 */
class DepartmentController extends Controller
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
               'only' => ['index','create', 'update', 'delete',   'listMembersJSON', 'listMembersHTML'],
               'rules' => [
                       [
                           'actions' => ['index','create','update','delete',   'ajax',   'listMembersJSON', 'listMembersHTML'],
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
     * Lists all Department models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Department::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Department model.
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
     * Creates a new Department model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Department();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Log::instance()->entry('Department created', print_r($model->getAttributes(), true));
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Department model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $log = new \app\models\Log;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $log->entry('DepartmentUpdate',print_r($model->getAttributes(),true));
            return $this->redirect(['index']);
        } else {
            $log->entry('DepartmentUpdate FAIL',print_r($model->getErrors(),true));
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Department model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $this->findModel($id)->delete();
        Log::instance()->entry('Department deleted', print_r($model->getAttributes(), true));

        return 0;
    }





    /**
     * Lists all department members.
     * @return JSON
     */
    public function actionListMembersJSON($id)
    {
		$users = User::find()
					->where("dept_id=$id")
					->orderBy('fullname')
					->all();

		$return = array();
		foreach($users as $u)
		{
			$return[] = $u->fullname;
		}

		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return json_encode($return);


    }



    /**
     * Lists all department members in html for modal display.
     * @return HTML
     */
    public function actionListMembersHTML($id)
    {
		$members = User::find()
					->where("dept_id=$id")
					->orderBy('fullname')
					->all();

		$html = "<div class='container'>
		         	<ul class='list-unstyled row'>";
		foreach($members as $m)
		{
			$html .= "<li class='list-item col-6 border p-1'>";
			$html .= $m->fullname;
			$html .= "</li>";
		}
		$html .= "	<ul>
		          </div>";

		Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
		return $html;
    }








    /**
     * Finds the Department model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Department the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Department::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
