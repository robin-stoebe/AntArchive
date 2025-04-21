<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

use app\assets\AppAsset;
use yii\web\JqueryAsset;
use yii\web\View;
use yii\widgets\Breadcrumbs;
use fedemotta\datatables\DataTables;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;

$url = Url::base();

?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Import User', ['import'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= DataTables::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'username',
            'fullname',
//            'ORGANIZATION',
            [
                'label'=>'Department',
                'value'=>function($model){
                            return $model->department();
                        }
            ],
            'rank',
            'user_type',
            //'EMAIL:email',

            ['class' => 'yii\grid\ActionColumn',
                             'header' => 'Actions',
             //'headerOptions' => ['style' => 'color:#337ab7'],
             'template' => '{view}{update}{delete}',
             'buttons' => [
               
                'update' => function ($url, $model) {
                   return Html::a('<i class="fas fa-edit"></i>', $url, [
                      'title' => Yii::t('app', 'update'),
                   ]);
                },
                'delete' => function ($url, $model) {
                   return " ".Html::a('<i class="far fa-trash-alt"></i>', '#', [
                      'title' => Yii::t('app', 'delete'),
                       'class'=>'delete',
                       'data' => [
                            'id' => $model->username,
                            'fullname' => $model->fullname,
                        ],
                   ]);
                }
             ],
            ],
        ],
    ]); ?>
</div>



<?php


// for other ajax delete buttons we have used  something like  $('.delete').click(function(){   but we're using DataTables here and .click() was not being triggered
// it was suggested to use the .on() method instead.  (example showed:  $('#tablename').on('click'...  but I'm not sure about what the datatable's id is. ) 

$script = "
    $('table').on('click', '.delete', function(){
            var username = $(this).attr('data-id');
            var fullname = $(this).attr('data-fullname');
            if (confirm('Are you sure you want to delete this User? (' + username + ' | ' + fullname + ')'))
            {
                $.ajax({
                    type: 'POST',
                    url: '$url/user/delete',
                    data:{id:username},
                    success:  window.location.reload(),
                    dataType: 'html'
                })
                return false;
            }
                
        })
    ";

$this->registerJs($script,View::POS_READY);

