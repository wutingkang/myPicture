<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "pic_info".
 *
 * @property integer $id
 * @property string $name
 * @property string $path
 * @property string $url
 * @property string $time
 * @property string $m_time
 * @property double $size
 * @property integer $type
 * @property integer $status
 */
class PicInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pic_info';
    }

    /**
     * @var save picture file
     */
    public $imageFile;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
//            [['name', 'path', 'url', 'time', 'm_time', 'size', 'type', 'status'], 'required'],
//            [['time', 'm_time'], 'safe'], //?
//            [['size'], 'number'],
//            [['type', 'status'], 'boolean'],
//            [['name', 'url', 'path'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'path' => 'Path',
            'url' => 'Url',
            'time' => 'Time',
            'm_time' => 'M Time',
            'size' => 'Size',
            'type' => 'Type',
            'status' => 'Status',
        ];
    }

    public function upload()
    {//echo isset($_POST['type']) ? $_POST['type'] : "fuck";die; $model->load(Yii::$app->request->post())
//echo var_dump(Yii::$app->request->post('type'));die;
        if ($this->validate()) { 

		  $path = $this->getPath();
		  $saveName = date("YmdHis") . '_' . rand(100, 999) . '.' . $this->imageFile->extension;
        	  $this->imageFile->saveAs($path . $saveName);
	   
	   
           //存入数据库,可以考虑加入事务，先存入数据库成功后再上传文件
		   $this->name = $this->imageFile->baseName;
			$this->path = $path;
			$this->url = dirname(Yii::$app->homeUrl).'photo/view/' . $saveName; //dirname(Yii::$app->homeUrl) ?
			$this->time = date("Ymd");
			$this->m_time = date("Ymd");
			$this->size = $this->imageFile->size;
			$this->type = Yii::$app->request->post('type', '0');
			$this->status = true;

			$this->save(false); //			

            /*Yii::$app->db->createCommand()->insert('pic_info', [
                'name' => $this->imageFile->baseName,
                'path' => $path,
                'url' => dirname(Yii::$app->homeUrl).'photo/view/' . $saveName, //dirname(Yii::$app->homeUrl) ?
                'time' => date("Ymd"),
                'm_time' => date("Ymd"),
                'size' => $this->imageFile->size,
                'type' => Yii::$app->request->post('type', '0'), //等同于: $type = isset($_POST['type']) ? $_POST['type'] : 0; 
                'status' => true,
            ])->execute();*/

            return true;
        } else {
            return false;
        }
    }

	public function getPath(){
		$path = '/usr/see/continue/tmp/file/pic/' . $this->type . '/' . date("Ym") . '/'; //edit the path in company

		if (!file_exists($path)){   //判断该目录是否存在  
      		if (false == mkdir($path, 0777, true)){ //第三个参数
				die('make file save path :' . $path . 'fail!');
			}
        }
		
		return $path;
	}

    public static function uploadPhoto($name)
    {
        $uploadedFile = UploadedFile::getInstanceByName($name);
        if($uploadedFile === null || $uploadedFile->hasError)
        {
            return null;
        }
        $ymd = date("Ymd");
        $save_path = dirname(Yii::$app->basePath).'\\web\\upload\\images\\'. $ymd . "\\";
        $save_url = dirname(Yii::$app->homeUrl).'/upload/images/' . $ymd . "/";
        if(! file_exists($save_path))
        {
            mkdir($save_path);
        }
        $file_name = $uploadedFile->getBaseName();
        $file_ext = $uploadedFile->getExtension();
        // 新文件名
        $new_file_name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $file_ext;
        $uploadedFile->saveAs($save_path . $new_file_name);
        return ['path' => $save_path, 'url' => $save_url, 'name' => $file_name, 'new_name' => $new_file_name, 'ext' => $file_ext];
    }
}
