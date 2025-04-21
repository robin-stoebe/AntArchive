<style>
    .title {
        font-size: 125%;
        font-weight: bold;
        /*margin: auto;*/
        width: 80%;
        clear:right;
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
        flex-flow: column;
  align-items: stretch;
  height: 500px;
        display: flex;
        flex: 1 1 auto;
    }
    
    </style>
<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= Html::encode($message) ?>
    </div>

    </p>

</div>
<div class="form">
    <div class="row">
      <div class="title"><?=$model->description;?></div>
    <!--<div class="intro"><?=$model->introduction;?></div>-->
      <div >available from::  <b><?=date('l M j, Y H:i A',strtotime($model->begin_date)).'</b> &nbsp; until &nbsp; <b>'.date('l M j, Y H:i A',strtotime($model->end_date));?></b></div>
    </div>
    <p>
</div>
     
