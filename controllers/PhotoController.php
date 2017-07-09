<?php

namespace app\controllers;

use app\models\PicInfo;

use Yii;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use app\models\UploadForm;
use yii\web\UploadedFile;
use app\models\PicType;

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
	  
        $typeData = PicType::find()->all(); //数目太多呢？

        if (Yii::$app->request->isPost) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

            if ($model->upload()) {// 文件上传成功
                return $this->render('index');
            }else{
                return $this->render('error');
            }
        }

        return $this->render('upload', ['model' => $model, 'typeData' => $typeData]);
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
