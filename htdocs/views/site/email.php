

Title: <?=$model->ballot->description."\r\n";?> 
Introduction: <?=$model->ballot->introduction."\r\n";?>
Keyphrase:  <?= $model->response->keyphrase."\r\n";?>

<?php foreach($model->ballot->question as $q){
            
            if($q->input_type!='Comment')
                 echo $this->render('emailQuestion',
                         ['model'=>$q,
                         'bottom'=>$bottom,
                         'auth'=>$model]);
            else
                 echo $this->render('emailComment',
                         ['model'=>$q,
                         'bottom'=>$bottom,
                         'auth'=>$model]);
            
            echo "\r\n\r\n";
    }?>
