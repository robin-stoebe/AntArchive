<?php

use yii\web\JqueryAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use app\assets\AppAsset;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Departments';
$this->params['breadcrumbs'][] = $this->title;

$url = Url::base();
?>
<div class="department-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Department', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'department',
            'abbrv',
            'academic',




            //['class' => 'yii\grid\ActionColumn'],


            // the default 'class' line above was created by crud generator
            // found code here:  https://stackoverflow.com/questions/38692362/how-to-change-view-update-and-delete-url-on-action-column-in-yii2
            //  to add more information.  I will add 'action-icon' to class so I can style it smaller

            [
             'class' => 'yii\grid\ActionColumn',
             'header' => 'Actions',
             //'headerOptions' => ['style' => 'color:#337ab7'],
             'template' => '<div class="row">&nbsp; {update}{delete}</div>',
             'buttons' => [
                'update' => function ($url, $model) {
                   return Html::a('<i class="fas fa-edit"></i>', $url, [
                      'title' => Yii::t('app', 'update'),
                   ]);
                },
                'delete' => function ($url, $model) {
                   return Html::a('<i class="far fa-trash-alt"></i>', '#', [
                      'title' => Yii::t('app', 'delete'),
                       'class'=>'delete',
                       'data' => [
                           'id' => $model->id,
                           'department' => $model->department,
                        ],
                ]);
                }
             ],
          ],





        ],
    ]); ?>
</div>



<?php

$script = "
        $('table').on('click', '.delete', function(){

            var id = $(this).attr('data-id');
            var department_name = $(this).attr('data-department');

            if (confirm('Are you sure you want to delete this Department? (' + department_name + ')'))
            {
        
                $.ajax({
                    type: 'POST',
                    url: '$url/department/delete?id=' + id,
                    data:{id:id},
                    success:  window.location.reload(),
                    dataType: 'html'
                })
                return false;
            }

        })
    ";

$this->registerJs($script,View::POS_READY);


