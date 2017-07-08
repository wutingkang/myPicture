<?php

namespace app\controllers;

use app\models\PicInfo;

use Yii;
use yii\web\Controller;
use app\models\UploadForm;
use yii\web\UploadedFile;

class PhotoController extends \yii\web\Controller
{
//    public function actionUpload()
//    {
//        $model=new PicInfo();
//
//        if($model->load(Yii::$app->request->post()) && $model->validate()){
//
//            $model->url = "";
//            $model->m_time = "";
//            $model->name = "";
//            $model->path = "";
//            $model->size = "";
//            $model->status = true;
//            $model->time = "";
//            $model->type = "";
//            $model->
//
//            if($model->save()){
//
//                PicInfo::uploadPhoto("");
//
//                return $this->redirect(['site\index']);
//            }
//        }
//
//        return $this->render('upload',[
//            'model'=>$model,
//        ]);
//    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionUpload()
    {
        $model = new PicInfo();

        if (Yii::$app->request->isPost) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');


            if ($model->upload()) {
                // 文件上传成功
                return $this->render('index');
            }
        }

        return $this->render('upload', ['model' => $model]);
    }

    public function actionEdit()
    {
        return $this->render('edit');
    }

    public function actionDelete()
    {
        return $this->render('delete');
    }


}
