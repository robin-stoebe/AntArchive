<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Configuration Items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="configuration-item-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Configuration Item', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'field',
            'description',

          [
             'class' => 'yii\grid\ActionColumn',
             'header' => 'Actions',
             //'headerOptions' => ['style' => 'color:#337ab7'],
             'template' => '<div class="row">{view}{update}{delete}</div>',
             'buttons' => [
                'update' => function ($url, $model) {
                   return Html::a('&nbsp; <i class="fas fa-edit"></i>', $url, [
                      'title' => Yii::t('app', 'update'),
                   ]);
                },
                'delete' => function ($url, $model) {
                   return Html::a('&nbsp; <i class="far fa-trash-alt"></i>', '#', [
                      'title' => Yii::t('app', 'lead-delete'),
                      'username' =>$model->id,
                       'class'=>'delete',
                   ]);
                }
             ],
          ],
        ],
    ]); ?>
</div>
