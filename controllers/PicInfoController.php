<?php

namespace app\controllers;

use Yii;
use app\models\PicInfo;
use app\models\PicInfoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\PicType;
use yii\web\UploadedFile;

/**
 * PicInfoController implements the CRUD actions for PicInfo model.
 */
class PicInfoController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Upload a new PicInfo model.
     * If upload is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionUpload()
    {
        $model = new PicInfo();
        $model->setScenario('upload');

        $typeData = PicType::find()->all(); //数目太多呢？

        if (Yii::$app->request->isPost) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

            if ($model->upload()) {// 文件上传成功

                return $this->actionIndex();
            }else{
                echo "file error";
            }
        }

        return $this->render('upload', ['model' => $model, 'typeData' => $typeData]);
    }

    /**
     * Lists all PicInfo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PicInfoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PicInfo model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Updates an existing PicInfo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->setScenario('update');

        if (Yii::$app->request->isPost) {

            if ($model->updatePic()){
                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                echo "name error";
            }

        } else {
            $typeData = PicType::find()->all(); //数目太多呢？

            return $this->render('update', [
                'model' => $model,
                'typeData' => $typeData,
            ]);
        }
    }

    /**
     * Deletes an existing PicInfo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();

        $this->findModel($id)->deletePic($id);

        return $this->redirect(['index']);
    }

    /**
     * Finds the PicInfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PicInfo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PicInfo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
