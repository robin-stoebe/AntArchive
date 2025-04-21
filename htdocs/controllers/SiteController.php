<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;

use app\components\Debug;
use app\components\Maillist;
use yii\console\ExitCode;
use app\models\Configuration;

use app\models\LoginForm;
use app\models\Log;
use app\models\ContactForm;
use app\models\GradSuperlist;
use app\models\User;
use app\models\Titlecode;
use app\models\MajorList;



class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {

        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['login', 'logout', 'signup'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login', 'signup'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];


    }
         
    
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
  
           public function beforeAction($action){
            
            // Check only when the user is logged in
            if ( !Yii::$app->user->isGuest)  {
               if ( Yii::$app->session->get('userSessionTimeout') < time() ) {
                   // timeout
                   Yii::$app->user->logout();
                   return $this->redirect(['/site/logout']);  //
                   return false; 
               } else {
                   Yii::$app->session->set('userSessionTimeout', 
                            time() + Yii::$app->params['authTimeout']); 
                    

               }
               
            } 

            return parent::beforeAction($action);
        }         
 
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        switch (Yii::$app->params['authenticationType'])
        {
            case "WebAuth":
                $auth = new Yii::$app->WebAuth;
                break;
            case "shibboleth":
                $auth = new Yii::$app->shibboleth;
                break;
        }

        $login = new LoginForm();
        if ( $auth->isLoggedIn() && !isset(Yii::$app->user->identity->username))
            $login->login();

        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {


		switch (Yii::$app->params['authenticationType'])
		{


			case "ICSLDAP":

		        if (!Yii::$app->user->isGuest) {
		            return $this->goHome();
		        }

		        $model = new LoginForm();
		        if ($model->load(Yii::$app->request->post()) && $model->login()) {
		            return $this->goBack();
		        }

		        $model->password = '';
		        return $this->render('login', [
		            'model' => $model,
		        ]);

				break;



			case "WebAuth":

				if (!Yii::$app->user->isGuest) {
                                     return $this->goHome();
				}

		        $model = new LoginForm();

				//if ($model->load(Yii::$app->request->post()))
				//{

					if ($model->login())
					{
						return $this->goHome();

					}

		        //}

				//echo " ##### ";
                                        
		        $model->password = '';
		        return $this->render('login', [
		            'model' => $model,
		        ]);

    			break;



            case "shibboleth":
    
                    $auth = new Yii::$app->shibboleth;
    
                    if ($auth->isLoggedIn())
                    {

                        // oit auth seems done, still need to do yii login
                        $model = new LoginForm();
                        if ($model->login())
                        {
                            //var_dump(Yii::$app->user->identity);
                            return $this->goBack();
                        }
    
                    }
                    else
                    {
                        // go to OIT's Shibboleth login page
                        $auth->login();
                    }
    
                    break;


		}


    }






    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        switch (Yii::$app->params['authenticationType'])
		{

			case "WebAuth":
                $auth = new Yii::$app->WebAuth;
                if ($auth->isLoggedIn())
                    $auth->logout();
                break;

			case "shibboleth":
                $auth = new Yii::$app->shibboleth;
                if ($auth->isLoggedIn())
                    $auth->logout();
                break;

        }


        Yii::$app->user->logout(true);  
        @session_destroy();
        return $this->redirect(['/site/index']);
    }

  

    public function actionCron()
    {
        global $debug;
        
        $debug=0;
        
        $email = Configuration::configValue('contact');

        $model = new TitleCode();
        $codes =$model->missingTitlecodes();

        $model = new Maillist();
        $lists = $model->diffLists();
        $body = $this->renderPartial('/titlecode/diffText',['model'=>$model,'lists'=>$lists,'titlecodes'=>$codes]);
        mail($email,'maillist updates',$body);
        Log::cronEntry('sent maillist diffs',"to:$email\n$body");
	

        return ExitCode::OK;
    }


    public function actionAjaxTitlecode(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id=$_POST['id'];
        $list=$_POST['list'];
        
        $titlecode = Titlecode::findOne($id);
        if($list=='blank')
            $list=NULL;
            
        $titlecode->maillist=$list;
        if($titlecode->save())
            Log::entry('update titlecode list',print_r($titlecode->getAttributes(),true));
        else
        Log::entry('error titlecode list',print_r($titlecode->getErrors(),true));

        return 0;
    }


    public function actionAjaxMajorcode(){
        $id=$_POST['id'];
        $list=$_POST['list'];
        $level=$_POST['level'];
        
        $marjorlist = MajorList::findOne($id);
        if($list=='blank')
            $list=NULL;
        $marjorlist->maillist=$list;
        $marjorlist->level=$level;
        if($marjorlist->save())
            Log::entry('update marjorlist list',print_r($marjorlist->getAttributes(),true));
        else
        Log::entry('error marjorlist list',print_r($marjorlist->getErrors(),true));

        return;
    }
    

    public function actionAjaxListcode(){
        $id=$_POST['id'];
        $super=$_POST['super'];
        $oldsuper=$_POST['oldsuper'];
        $list=$_POST['list'];
        

        if($super=='blank' and !empty($oldsuper)){
            $entry = GradSuperlist::find()->where(['super'=>$oldsuper,'member'=>$list])->one();
            $entry->delete();
        } else {
            
            if(empty($oldsuper) or 
               $oldsuper=='blank' or 
               !($memlist = GradSuperlist::find()
                    ->where(['super'=>$oldsuper,'member'=>$list])->one())
            ){
                $memlist= new GradSuperlist();
                $memlist->member= $list;
            }
            $memlist->super = $super;
            if($memlist->save())
                    Log::entry('update grad super list',print_r($memlist->getAttributes(),true));
            else
                Log::entry('error marjorlist list',print_r($memlist->getErrors(),true));

        }
        return;
    }
          
}
