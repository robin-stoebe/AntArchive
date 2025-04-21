<?php

namespace app\models;
use app\models\AnalystFor;
use app\models\Department;
use app\models\Lookup;
use app\models\LookupType;
use app\models\RankConversion;
use Yii;
use app\models\UCPathDepartments;
use app\components\ucildap;
use app\components\Uciauthldap;
use app\components\Debug;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Url;


class User extends ActiveRecord implements \yii\web\IdentityInterface
{


   //public $user_type = "something";
    
    public $returnUrl = ["site/index"];


public static function tableName() { return 'user'; }

   /**
 * @inheritdoc
 */
  public function rules()
  {
        return [
            [['username','fullname','user_type'], 'required'],
            [['user_type'], 'string'],
            [['fullname'],'string','max'=>80],
            [['email'], 'email'],
            [['username','email'], 'unique'],
            [['username'], 'string', 'max' => 30],
            [['rank'], 'string', 'max' => 40],
            [['email'], 'string', 'max' => 200],

            [['user_type','dept_id','affiliation'], 'safe'],



        ];
  }

    public function isUser($u){
        if($this->findOne($u))
            return true;
        else
            return false;
    }



    public function isAnalystFor($d){
        $u = $this->username;
        if( $this->user_type=='ADMIN')
            return true;
        
        
        
        if(!is_numeric($d)){
            $dept=Department::find()->where("department=:d",[':d'=>$d])->one();
            $did=$dept->id;
        } else {
            $did=$d;
        }
        
        if($this->user_type=='ANALYST' and $this->dept_id==$did)
            return true;
        
   
        
        
        $af = AnalystFor::find()
                ->where("uname=:u and department_id=:d",[':u'=>$u,':d'=>$did])
                ->one();
        if($af==false)
            return false;
        else
            return true;
    }
    public static function findIdentity($id) {

        //$user = self::find()
        //        ->where([
        //            "user_id" => $id
        //        ])
        //        ->one();

        $user = self::find()
                ->where([
                    "username" => $id
                ])
                ->one();

        if (!$user) {

                    $new_identity = new User;
                    $new_identity->username = $id;
                    $new_identity->user_type = "user";
                    return $new_identity;

        }

        return new static($user);

    }

    
    public function adminTable(){

        $groups = implode(',',(array)$this->groupMember());

        $url = Url::base();
        return array(
            'Actions'=>Html::a("<i class=\"fa fa-pencil\"></i>",Url::toRoute(["user/update",'id'=>$this->username])).
            "&nbsp; ".Html::a("<i class=\"fa fa-trash\"></i>",Url::toRoute(["user/delete",'id'=>$this->username]),['class'=>'delete','data-id'=>$this->username,'data-fullname'=>$this->fullname]),
            'Username'=>$this->username,'Fullname'=>$this->fullname,
                'Department'=>$this->department->department,
                'User Type'=>$this->user_type,
                'Groups'=>$groups,
            );
    }


    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $userType = null) {

        $user = self::find()
                ->where(["accessToken" => $token])
                ->one();
        if (!count($user)) {
            return null;
        }
        return new static($user);
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username) {

       $user = self::find()
                ->where([
                    "username" => $username
                ])
                ->one();

        //if (!count($user)) {
        if (!$user) {
            return null;
        }

        return new static($user);

    }

    public static function findByUser($username) {
        $user = self::find()
                ->where([
                    "username" => $username
                ])
                ->one();
        if (!count($user)) {
            return null;
        }
        return $user;
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        //return $this->id;
        //return $this->user_id;
        return $this->username;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->authKey === $authKey;
    }






    /**
     * @inheritdoc
     */
    public function getUserType() {
        return $this->user_type;
    }




    public static function  isAdmin($u){
        if(empty($u))
            return false;

         $user = User::find()
                ->where(['username'=>$u])
                ->one();

        if($user->user_type=='ADMIN')
            return TRUE;

        return false;
    }


    public function isAnalyst($u){
        if(empty($u))
            return false;

        $user = User::find()
                ->where(['username'=>$u])
                ->one();

        if($user->isAdmin($u))
            return TRUE;

        if($user->user_type=='ANALYST')
            return TRUE;

        return false;
    }

    
    public function canEdit(){
        if(User::isAdmin(Yii::$app->user->identity->username))
            return true;
        
         $user = User::findOne(Yii::$app->user->identity->username);
         $depts = $user->myDepartments();
         if((in_array($this->dept_id,$depts) or $this->dept_id ==NULL)
                 and
             !in_array($this->user_type,['ADMIN','ANALYST'] ))
                 return true;
         
         return false;
    }

    public function department(){
        if($this->dept_id!=null and $this->dept_id>0){
            if($dept = Department::findOne($this->dept_id))
                return $dept->department;
            else
                return 'no department set';
        } else
            return false;
    }

    
    public function myDepartments(){
        $departments[]=$this->dept_id;
        
        foreach($this->analystFor as $a)
            $departments[]=$a->department_id;
        
        return $departments;
    }

    public function myDepartmentOptions(){
        $departments[$this->dept_id]=$this->department->department;
        
        foreach($this->analystFor as $a)
            $departments[]=$a->department->department;
        
        
        
        return $departments;
    }
        

    public static function userTypeOptions(){
        
        //    return  Lookup::LookupOptions('UserType',['description','description']);

            $lookupType= LookupType::find()->where(['name'=>'UserType'])->one();
            $lookups = Lookup::find()->where('type_id =  '. $lookupType->lookup_type_id)->orderBy('weight, description')->all();
            
            
            if(User::isAdmin(Yii::$app->user->identity->username))
                return yii\helpers\ArrayHelper::map($lookups, 'description', 'description');
            
            else {
                foreach($lookups as $l){
                    if (in_array($l->description,['ADMIN','ANALYST']))
                        continue;
                    $types[$l->description]=$l->description;
                }
                return $types;
            }

    }

    public static function rankTypeOptions(){
            return  Lookup::LookupOptions('Rank',['description','description']);


            $lookups = Lookup::find()->where('type_id = '. $lookupid->lookup_type_id)->orderBy('weight, description')->all();

            return yii\helpers\ArrayHelper::map($lookups, 'description', 'description');

    }


    public static function findByUcinetid($ucinetid) {

       //$user = self::find()
       //         ->where([
       //             "ucinetid" => $ucinetid
       //         ])
       //         ->one();

       $user = self::find()
                ->where([
                    "username" => $ucinetid
                ])
                ->one();

        //if (!count($user)) {
        if (!$user) {
            return null;
        }

        return new static($user);

    }

    public function updateRank($people){
        
      $levels = [
                'Full'=>'PROFESSOR',
                'Associate'=>'ASSOCIATE_PROFESSOR',
                'Assistant'=>'ASSISTANT_PROFESSOR',
                'Lecturer SOE'=>'LECTURER_SOE',
                'Lecturer PSOE'=>'LECTURER_PSOE'
            ];
        
        $log = new \app\models\Log();

        foreach($people as $import){
            $rec = unserialize($import);
            Debug::debug($rec,'going to update rank');
            if($user= User::findOne($rec['username'])){
                $user->rank = $levels[$rec['facultylevel']];
                if($user->validate() && $user->save()){
                    $log->entry('updated rank for user',print_r($user->getAttributes(),true));
                } else {
                    $log->entry('imported user error',print_r($user->getErrors(),true));
                }
            }
        }
        
    }


    public function groupMember(){
        $groups = [];
        foreach($this->group as $g)
            $groups[]=$g->group->group_name;

        return $groups;
    }


    public function import($people,$utype='USER'){
        $log = new \app\models\Log();

        foreach($people as $import){
            $rec = unserialize($import);
//            print_r($rec);
            $user= new User();
            $user->username=$rec['UCINet ID'];
            $user->fullname=$rec['Name'];
            $user->user_type=$utype;
            $user->email=$user->username."@uci.edu";
            
            
            if(!empty($rec['Title'])) {
                $user->rank = $rec['Title'];
            } else {
                $rank = explode(':',$rec['uciaffiliation']);
                $user->rank= ucfirst($rank[0]);
            }
            $user->dept_id = Department::findIDByAffiliation($rec['uciaffiliation']);
            if($user->validate() && $user->save()){
                $log->entry('imported user',print_r($user->getAttributes(),true));
            } else {
                $log->entry('imported user error',print_r($user->getErrors(),true));
            }
        }
    }

    public function findUCIUsers($username){
        $model = new User();

        $people=[];
        $toimport=[];


        $toimport=[];
        $ucildap = new ucildap();

        $people = $ucildap->find($username);


        foreach($people as $p){

            if($model->isUser($p['UCINet ID'])){
                $v = 'In App';
            } else
                $v = $p;


            $toimport[]=
                [
                    'Import'=> $v,
                    'Name'=>$p['Name'],
                    'UCINet ID'=>$p['UCINet ID'],
                    'Affiliation'=>$p['uciaffiliation']
                ];

        }

        return $toimport;
    }

    public function missingFaculty(){
        global $debug;
        $model = new User();
        $people=[];
        $toimport=[];

        $depts = [];
        $toimport=[];

        $users = User::find()->asArray()->all();

        foreach($users as $u){
            $unames[]=$u['username'];
        }

        $ucidepartments = \app\models\UCPathDepartment::find()->asArray()->all();

        Debug::debug($ucidepartments);
        foreach($ucidepartments as $d){
            $depts[]=$d['ucihomedepartmentcode'];
        }

        $ucildap = new ucildap();

        $q=['uciaffiliation'=>'faculty','ucihomedepartmentcode'=>$depts];

        $ucildap->query($q);

        for ($ucildap->rewind(); $ucildap->valid(); $ucildap->next()) {
              $v = $ucildap->current();
              Debug::debug($v);
             unset($rec);

             $rec = array("Name"=> $v[sn][0].", ".$v[givenname][0],"UCINet ID"=>$v[uid][0],'Title'=>$v[title][0]);

             if(strtolower($v[uciaffiliation][$v[uciaffiliation][count]-1])=='student')
                $rec["uciaffiliation"] = "Student: " . $v[major][0] . " - " . $v[studentlevel][0];
             else
                $rec["uciaffiliation"] = $v[uciaffiliation][$v[uciaffiliation][count]-1].": " . $v[department][0];

            if(!in_array($rec['UCINet ID'],$unames)){

                $toimport[]=
                    [
                        'Import'=> $rec,
                        'Name'=>$rec['Name'],
                        'UCINet ID'=>$rec['UCINet ID'],
                        'Title'=>$rec['Title'],
                        'Affiliation'=>$rec['uciaffiliation']
                    ];
            }
        }

        return $toimport;
    }



    public function checkRankFaculty(){
        global $debug;
        $model = new User();
        $people=[];
        $toimport=[];

        $depts = [];
        $toimport=[];
        $ranks=[];
        
        /// we need to look at other ways to figure rank ?? title code?
        
        
          $levels = [
                'Full'=>'PROFESSOR',
                'Associate'=>'ASSOCIATE_PROFESSOR',
                'Assistant'=>'ASSISTANT_PROFESSOR',
                'Lecturer SOE'=>'LECTURER_SOE',
                'Lecturer PSOE'=>'LECTURER_PSOE'

            ];

        
        $user = User::findOne(Yii::$app->user->identity->username);
        $depts=$user->myDepartments();
        
        Debug::debug($depts,'my departments');
        
        
        $users = User::find()->where(['user_type'=>'FACULTY'])->asArray()->all();
        Debug::debug(print_r($users,true),'checking these users');

        $ucildap = new Uciauthldap();

        foreach($users as $u){
            
            if($user->user_type=='ANALYST' and !in_array($u[dept_id],$depts))
                    continue;
            
            $q=['uid'=>$u[username]];

            $ucildap->query($q);
            $ucildap->rewind();
            $v = $ucildap->current();
              Debug::debug($v);
             unset($rec);

             
             if(!in_array($v['facultylevel'][0],$ranks))
                     $ranks[]=$v['facultylevel'][0];
             
             if($u[rank]==$levels[$v['facultylevel'][0]])
                 continue;
             
             
             $rec = array("username"=> $u[username]);
                        $rec['rank']=$u[rank];
                        $rec['facultylevel']=$v['facultylevel'][0];
                        

                $toimport[]=
                    [
                        'Import'=> $rec,
                        'Name'=>$u['fullname'],
                        'UCINet ID'=>$u['username'],
                        'rank'=>$u['rank'],
                        'uciprimarytitle'=>$v['uciprimarytitle'][0],
                        'uciprimarytitlecode'=>$v['uciprimarytitlecode'][0],
                        'facultylevel'=>$v[facultylevel][0],
                        'Title'=>$v['title'][0],
                        'Affiliation'=>$v[uciaffiliation][$v[uciaffiliation][count]-1].": " . $v[department][0]
                    ];
        }
        Debug::debug($toimport,'checkRankFaculty to return');
        Debug::debug($ranks,'we found the following Ranks');
        return $toimport;
    }






	public function getDeptId() {
	    return $this->dept_id;
	}


    public function getAnalystFor(){
        return $this->hasMany(AnalystFor::className(),['uname'=>'username']);
    }


    public function getDepartment()
    {
        //if($this->dept_id != null and $this->dept_id > 0)
        //{
		//    return $this->hasOne(Department::className(), ['id' => 'dept_id']);
        //}

		return $this->hasOne(Department::className(), ['id' => 'dept_id']);

    }


    public function getGroup(){
		return $this->hasMany(GroupMember::className(), ['uname' => 'username']);

    }

     public function getRankConversion()
    {
     

		return $this->hasOne(RankConversion::className(), ['rank' => 'rank']);

    }

    public function memberOptions(){
        $options =[];
        $all = User::find()->orderBy('fullname')->all();
        foreach($all as $d){
            $options['i-'.$d->username]=$d->fullname;
        }
        return $options;
    }

    public function with($type,$id){
        $users=array();
        
        $recs= User::find()->where([$type=>$id])->all();
        foreach($recs as $u){
            $users[$u->username]=$u->fullname;
        }
        return $users;
    }
    
    public function withModel($type,$id){
        $users=array();
        
        $recs= User::find()->where([$type=>$id])->all();
        foreach($recs as $u){
            $users[$u->username]=$u;
        }
        return $users;
    }

    
    public function email(){
        if(empty($this->email))
            return $this->username."@uci.edu";
        else
            return $this->email;
    }
}
