<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\UserSearch;
use app\models\Department;
use app\models\Log;
use app\components\Debug;
use app\components\Logout;


use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\components\AccessRule;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;






/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
               'only' => ['index','create', 'update', 'delete','import','faculty','facultyRankUpdate'],
               'rules' => [
                   [
                       'actions' => ['index','create','update','delete','import','faculty','facultyRankUpdate'],
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $userTable=[];
     
        $model = new User;
        
        $user = User::findOne(Yii::$app->user->identity->username);
        $logged_in_username = "";
	$logged_in_username = Yii::$app->user->identity->username;
       
        if(User::isAdmin($logged_in_username)){
            $users = User::find()->all();
        } else {
            $depts = $user->myDepartments();
            
            $users=User::find()->where(['dept_id'=>$depts])->orWhere('dept_id is null')
                    ->andWhere("user_type not in ('ADMIN','ANALYST')  ")->all();
        }
        
        foreach($users as $u){
            $userTable[]=$u->adminTable();
        }

        return $this->render('admin', [
            'model'=>$model,
            'table' => $userTable,
        ]);
        

        
    }

    /**
     * Displays a single User model.
     * @param string $id
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        $user = User::findOne(Yii::$app->user->identity->username);
         
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
               \app\models\Log::entry('create user',print_r($model->getAttributes(),true));
            return $this->redirect(['index']);
        }

        
         if(User::isAdmin(Yii::$app->user->identity->username)){
            $departmentOptions = ArrayHelper::map(Department::find()->all(), 'id', 'department');
         } else {
            $departmentOptions = $user->myDepartmentOptions();
         }
        
        return $this->render('create', [
            'model' => $model,
            'departmentOptions'=>$departmentOptions,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if(!$model->canEdit()){
            Log::entry('failed user edit', Yii::$app->user->identity->username.":: tried to edit user :: ".$model->username);
            return $this->render('error',['model'=>$model,'stmt'=>'You do not have access to edit this user']);

        }
        
        
         $user = User::findOne(Yii::$app->user->identity->username);
         
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
               \app\models\Log::entry('update user',print_r($model->getAttributes(),true));
            return $this->redirect(['index']);
        }
        
        
         if(User::isAdmin(Yii::$app->user->identity->username)){
            $departmentOptions = ArrayHelper::map(Department::find()->all(), 'id', 'department');
         } else {
            $departmentOptions = $user->myDepartmentOptions();
         }
        
        
        return $this->render('update', [
            'model' => $model,
            'departmentOptions'=>$departmentOptions,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete()
    {
        $id = $_POST['id'];
        $model = User::findOne(['username'=>$id]);
        if(!$model->canEdit()){
            Log::entry('failed user delete', Yii::$app->user->identity->username.":: tried to delete user :: ".$model->username);
            return FALSE;

        }        
        
        
        Log::entry('delete user', $id);
        $model->delete();

        if(!Yii::$app->request->isAjax)

            return $this->redirect(['index']);
        
        
        return 0;
    }

    
    public function actionImport(){
        $model = new User;
        $username='';
//        $ucidepartments = User::ucidepartmets();

        
            if(!empty($_POST['toimport'])) {
                
               // print_r($_POST);exit;
                 User::import($_POST['toimport']);
                
                return $this->redirect(['index']);
            } elseif(!empty($_REQUEST['username'])) {
                
                 
                $username=$_REQUEST['username'];
                if(empty($_GET['username']) or $_GET['username']!=$username)
                    return $this->redirect(['import','username'=>$username]);
                if(!empty($username)){
                    
                     $toimport = User::findUCIUsers($username);

                    
                }
            
            }
        
        
        return $this->render('searchuci', [
            'model' => $model,
            'username'=>$username,
            'people'=>$toimport,
           
        ]);
    }
    
    
    public function actionFacultyRankUpdate(){
           $model = new User();
      
        
        $toupdate=[];
        $username='';


        
        if(!empty($_POST['toimport'])) {

            $model->updateRank($_POST['toimport']);


        }  



        $toupdate = $model->checkRankFaculty();

        return $this->render('updateRankFaculty', [
                            'model' => $model,
                            'people'=>$toupdate,
                        ]
            );
    }
    
    
    public function actionFaculty() {
        
        $model = new User();
      
        
        $toimport=[];
        $username='';


        
        if(!empty($_POST['toimport'])) {

           // print_r($_POST);exit;
            $model->import($_POST['toimport'],"FACULTY");


            return $this->redirect(['index']);
        }  



        $toimport = $model->missingFaculty();

        return $this->render('missingFaculty', [
            'model' => $model,
            'people'=>$toimport,
           
        ]);
    }
    
    
    
    
    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
