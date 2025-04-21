<?php

namespace tests\models;

use app\models\User;

use app\tests\fixtures\UserFixture;
use app\tests\fixtures\AnalystForFixture;
use app\tests\fixtures\DepartmentFixture;


class UserTest extends \Codeception\Test\Unit
{



  



    public function testFindUserById()
    {


        $model = new User;
        $user = $model->findIdentity('wdcohen');
        $this->assertEquals($user->fullname,"Cohen, William");
        $this->assertEquals($user->username,"wdcohen");
        $this->assertEquals($user->getUserType(),"ADMIN");
        $this->assertTrue($user->isAnalystFor(6),'wdcohen is admin');
        $this->assertTrue($model->isAdmin('wdcohen'),'wdcohen admin');
        $this->assertTrue($model->isUser('wdcohen'),'wdcohen is a user');


        $user = $model->findIdentity('rbusta');
        $this->assertNotEquals($user->fullname,"Rosemary");
        $this->assertEquals($user->getUserType(),"ANALYST");
        $this->assertEquals($user->department(),"Statistics");
        $this->assertEquals($user->getDeptID(),"8");
        $this->assertTrue ($user->isAnalystFor(8),'rbutsta is Stats');
        $this->assertTrue ($user->isAnalystFor('Statistics'),'rbutsta is Analystfor Stats');
        $this->assertFalse($user->isAnalystFor(6),'rbutsta is not CS');
        $this->assertFalse($model->isAdmin('rbusta'),'rbusta not and admin');
        $this->assertTrue($model->isAnalyst('rbusta'),'rbusta is an analyst');


        $user = $model->findIdentity('somethingelse');
        $this->assertEquals($user->fullname, null);


        $this->assertFalse($model->isUser('dpack'),'dpack is NOT a user');

        $this->assertTrue($model->isUser('hbyrnes'),'hbyrnes is a user');
        $user = $model->findIdentity('hbyrnes');
        
        $this->assertFalse($user->isAnalystFor(8),'hbyrnes is not analystfor 8');
        $this->assertTrue($user->isAnalystFor(5),'hbyrnes is analystfor 5');
        $this->assertTrue($user->isAnalystFor(6),'hbyrnes is analystfor 6'. print_r($user->getAttributes(),true));
        $this->assertEquals($user->department(),"Division of Computing");
        $this->assertEquals($user->getDeptID(),"5");
        

/*
        $user =$model->findIdentity(999);
        $this->assertEquals($user->username,999);
        $this->assertEquals($user->user_type,"user");

        $user =$model->findIdentity('admin');
        $this->assertEquals($user->username,"admin");
        $this->assertEquals($user->user_type,"user");
*/



    }



    public function testSomethingElse()
    {

    }



}
