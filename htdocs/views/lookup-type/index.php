<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
<?php

use yii\web\JqueryAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use app\assets\AppAsset;
use yii\web\View;


/* @var $this yii\web\View */
/* @var $searchModel app\models\LookupTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lookup Types';
$this->params['breadcrumbs'][] = $this->title;

$url = Url::base();

?>
<div class="lookup-type-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Lookup Type', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name:ntext',
            ['class' => 'yii\grid\ActionColumn',
             'header' => 'Actions',
             //'headerOptions' => ['style' => 'color:#337ab7'],
             'template' => '{update} {list} {delete}',
             'buttons' => [
//                'update' => function ($url, $model) {
//                   return Html::a('<i class="fas fa-edit"></i>', $url, [
//                      'title' => Yii::t('app', 'update'),
//                   ]);
//                },
                'list' => function ($url, $model) {
                   return Html::a('<i class="fas fa-edit"></i>', $url, [
                      'title' => Yii::t('app', 'list'),
                   ]);
                },
                'delete' => function ($url, $model) {
                   return Html::a('<i class="far fa-trash-alt"></i>', '#', [
                      'title' => 'Delete LookupType',
                       'class'=>'delete',
                       'data' => [
                            'id' => $model->lookup_type_id,
                            'name' => $model->name,
                            'method' => 'post',
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
        $('.delete').click(function(){

            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');

            if (confirm('Are you sure you want to delete this Lookup Type? (' + name + ')'))
            {

                $.ajax({
                    type: 'POST',
                    url: '$url/lookup-type/delete',
                    data:{id:id},
                    success:  window.location.reload(),
                    dataType: 'html'
                })
                return false;
            }

        })
    ";

$this->registerJs($script,View::POS_READY);


