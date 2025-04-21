<?php





//
//  WebAuth requires the username input name to be "ucinetid"
//   Using Yii's standard ActiveForm form->field method to create each input creates names with "LoginForm[]' (the model name) notation
//    so we'll manually create the html code for formatting the divs correctly just as form->field would
//
//  Also for WebAuth-type authentication set the URL in ActiveForm::begin's 'action', where the form needs to submit to
//





/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


$this->title = 'Login';

//$this->params['breadcrumbs'][] = $this->title;


?>



<div class="site-login   col-lg-6 col-md-12 mx-auto   ">


	<div class='card'>

		<h3 class='card-header'><?= Html::encode($this->title) ?></h3>

			<div class='card-body'>



<?php



		$activeform_params_array = [
		                             'id' => 'login-form',
		                             'layout' => 'horizontal',
		                             'options' => [ 'class' => 'mt-4' ],
		                             'fieldConfig' => [
		                                'horizontalCssClasses' => [
		                                   'wrapper' => 'col-lg-12',
		                                ],
		                             ],
		                          ];



//switch ("ICSLDAP")
switch ("WebAuth")
//switch (Yii::$app->params['authenticationType'])
{

	case "ICSLDAP":

		echo "<p>Please input your <b>ICS login credentials</b>:</p>";


		$form = ActiveForm::begin($activeform_params_array);


        echo $form->field($model, 'username')->textInput(['autofocus' => true]);
        echo $form->field($model, 'password')->passwordInput();

        break;

	case "WebAuth":

		echo "<p>Please input your <b>UCINetID login credentials</b>:</p>";


		$activeform_params_array['action'] = 'https://login.uci.edu/ucinetid/webauth';

		$form = ActiveForm::begin($activeform_params_array);
		$auth = new Yii::$app->WebAuth;
        echo Html::hiddenInput('return_url',$auth->url);


		echo "

		      <div class='form-group field-ucinetid'>
		         <label class='control-label col-sm-3' for='ucinetid'>UCINetID</label>
		         <div class='col-lg-12'>
		            <input type='text' id='ucinetid' class='form-control' name='ucinetid' autofocus='' style='cursor: auto;' autocomplete='off'>
		            <div class='help-block '></div>
		         </div>
		      </div>

		      <div class='form-group field-password'>
		         <label class='control-label col-sm-3' for='password'>Password</label>
		         <div class='col-lg-12'>
		            <input type='password' id='password' class='form-control' name='password' value='' style='' autocomplete='off'>
		            <div class='help-block '></div>
		         </div>
		      </div>

		     ";



//		echo "UCINetID <input type='text' name='ucinetid'> <br>
//		      Password <input type='password' name='password'>
//
//		     ";


        break;
}

?>





            <div class="text-center">
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login_button', 'value' => 'Login']) ?>
            </div>

    <?php ActiveForm::end(); ?>



		</div>

	</div>



</div>



