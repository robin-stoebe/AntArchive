<?php


use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\base\Widget;



/* @var $this yii\web\View */
/* @var $model app\models\Ballot */
/* @var $form yii\widgets\ActiveForm */
?>
<?php if(empty($text)){?>
<style>
    .title {
        font-size: 125%;
        font-weight: bold;
        align-content: center;
        margin: auto;
        width: 80%;
        text-align: center;
    }
    .intro {
        padding-bottom: 3em;
    }
    .row {
        margin:0px;
    }
    .question {
        width: 55%;
        border: 1px solid grey;
        border-bottom: 0px solid grey;
        padding: 10px;
    }
    .answer {
        width: 40%;
        border: 1px solid grey;
        border-left: 0px solid black;
        border-bottom: 0px solid grey;
        padding: 10px;
    }
    .qid {
        width: 25px;
        font-weight: bold;
    }
    .qtext {
        width: 90%;
        font-weight: bold;
    }
    
    .bottom {
        border-bottom: 1px solid grey;

    }
    
    .form {
        min-width: 930px;
    }
    
    </style>
<?php } ?>
<div class="form">
    <div class="title"><?=$model->ballot->description;?></div>
    <div class="intro"><?=$model->ballot->introduction;?></div>
    <div class="row">
        <div class="question column bottom">
            <b>Please enter a key phrase for this ballot (Optional)<br></b>
                <font color="#000000" size="-1">
                This will provide you a way to verify that your vote 
		was recorded.</font>
        </div>
        <div class="column answer bottom">
            <?= $model->response->keyphrase;?>
        </div>
    </div>

    <p />&nbsp;
    <p />
    <?php foreach($model->ballot->question as $q){
            $x++;
            $bottom='';
            
            if(count($model->ballot->question)==$x)
                $bottom='bottom';
            if($q->input_type!='Comment')
                 echo $this->render('reviewQuestion',
                         ['model'=>$q,
                         'bottom'=>$bottom,
                         'auth'=>$model]);
            else
                 echo $this->render('reviewComment',
                         ['model'=>$q,
                         'bottom'=>$bottom,
                         'auth'=>$model]);
    }?>
    <p />
    
</div>