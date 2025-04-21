<?php


namespace app\tests\fixtures;

use yii\test\ActiveFixture;
use app\models\User;

class UserFixture extends ActiveFixture
{
    public $modelClass = 'app\models\User';
    
     public $depends = ['app\tests\fixtures\AnalystForFixture',
                       'app\tests\fixtures\DepartmentFixture'
        ];
}
