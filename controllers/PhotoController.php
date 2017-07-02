<?php

namespace app\controllers;

use app\models\PicInfo;

class PhotoController extends \yii\web\Controller
{
    public function actionUpload()
    {
        $model=new PicInfo();

        if($model->load(Yii::$app->request->post()) && $model->validate()){

            $model->url = "";
            $model->m_time = "";
            $model->name = "";
            $model->path = "";
            $model->size = "";
            $model->status = true;
            $model->time = "";
            $model->type = "";
            $model->

            if($model->save()){

                PicInfo::uploadPhoto("");

                return $this->redirect(['site\index']);
            }
        }

        return $this->render('upload',[
            'model'=>$model,
        ]);
    }

    public function actionEdit()
    {
        return $this->render('edit');
    }

    public function actionDelete()
    {
        return $this->render('delete');
    }

    public function actionSearch()
    {
        return $this->render('search');
    }
}
