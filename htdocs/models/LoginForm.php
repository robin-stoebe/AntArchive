<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    //public $rememberMe = true;
    public $ucinetid;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {

		switch (Yii::$app->params['authenticationType'])
		{

			case "ICSLDAP":
        		return [
					// username and password are both required
					[['username', 'password'], 'required'],
					// password is validated by validatePassword()
					['password', 'validatePassword'],
		        ];
		        break;

			case "WebAuth":
		        return [
            		// password is validated by validatePassword()
            		['password', 'validatePassword'],
        		];
		        break;

        }

    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute='username', $params='')
    {

//echo " LoginForm.php --- validatePassword() ";



		switch (Yii::$app->params['authenticationType'])
		{



			case "ICSLDAP":

				if ( !Yii::$app->icsldap->ldap_connect($_POST['LoginForm']['username'], $_POST['LoginForm']['password']) )
                                {
					$this->addError($attribute, 'Incorrect username or password.');
				}

				break;



			case "WebAuth":

				$auth = new Yii::$app->WebAuth;

				if(!$auth->isLoggedIn())
				{
					$auth->login();
				}

		        break;



		}



    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {



		switch (Yii::$app->params['authenticationType'])
		{



			case "ICSLDAP":
                                $auth = new Yii::$app->icsldap;
//				echo " LoginForm.php -- ICSLDAP ";

				if ($this->validate())
				{

//					echo " LoginForm.php -- ICSLDAP -- validated ";


					$this->_user = User::findOne($this->username);

					if (!$this->_user)
					{
						$new_identity = new User;
						$new_identity->user_id = $this->username;
						$new_identity->user_name = $this->username;
						$new_identity->user_type = $this->user_type;

						$this->_user = $new_identity;

					}
						Yii::$app->session->set('userSessionTimeout', 
									time() + Yii::$app->params['authTimeout']); 


					return Yii::$app->user->login($this->_user, 0);

				}


				break;



			case "WebAuth":

				$auth = new Yii::$app->WebAuth;

				if ($auth->isLoggedIn() || $auth->login()){

					$this->_user = User::findByUcinetid($auth->ucinetid);


					if (!$this->_user)
					{


						return null;


					}

					Yii::$app->session->set('userSessionTimeout', 
								time() + Yii::$app->params['authTimeout']); 

					return Yii::$app->user->login($this->_user, 0);


				}
				break;

				

			case "shibboleth":

				$auth = new Yii::$app->shibboleth;

					if ($auth->isLoggedIn())
					{
						//echo " validated, \$auth->ucinetid = ". $auth->ucinetid;

						$this->_user = User::findByUcinetid($auth->ucinetid);

						if (!$this->_user)
						{
							//echo "<h2> findByUcinetid($auth->ucinetid) </h2> didn't find matching user in table ";

							$new_identity = new User;

							//$new_identity->user_id = $this->username;
							$new_identity->user_name = $this->username;
							$new_identity->user_type = "USER";

							$$this->_user = User::insertU($auth, 'GUEST');

						}

						Yii::$app->session->set('userSessionTimeout', 
									time() + Yii::$app->params['authTimeout']); 

						return Yii::$app->user->login($this->_user, Yii::$app->params['authTimeout']);

					}

					break;



		}


		return false;


    }




    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
