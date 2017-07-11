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
        return '{{%info}}';
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
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg', 'maxSize' => 1024*1000, 'message' => '您上传的文件过大', 'on' => ['upload']],
            [['name'], 'string', 'max' => 255, 'on' => ['update']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'path' => 'Path',
            'url' => 'Url',
            'time' => 'Time',
            'm_time' => 'M Time',
            'size' => 'Size',
            'type' => 'Type',
            'status' => 'Status',
        ];
    }

    public function scenarios()
    {
        return [
            'upload' => ['imageFile'],
            'update' => ['name'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {

            $this->type = Yii::$app->request->post('PicInfo')['type']; //获取路径需要用到type
            $Dir = $this->getDir();
            $saveName = date("YmdHis") . '_' . rand(100, 999) . '.' . $this->imageFile->extension;
	   
            //存入数据库,可以考虑加入事务，先存入数据库成功后再上传文件
		    $this->name = $this->imageFile->baseName;
			$this->path = $Dir . $saveName;
			$this->url = 'http://www.storemypicture.com' . '/' . $this->type . '/' . date("Ym") . '/' . $saveName;
			$this->time = time();
			$this->m_time = $this->time;
			$this->size = $this->imageFile->size;
			//$this->type = //上面已经赋值
			$this->status = true;

			$this->save(false); //$this->validate() 验证过了


            $this->imageFile->saveAs($this->path);


//            Yii::$app->db->createCommand()->insert('pic_info', [
//                'name' => $this->imageFile->baseName,
//                'path' => $path,
//                'url' => dirname(Yii::$app->homeUrl).'photo/view/' . $saveName,
//                'time' => date("Ymd"),
//                'm_time' => date("Ymd"),
//                'size' => $this->imageFile->size,
//                'type' => Yii::$app->request->post('PicInfo')['type'],
//                'status' => true,
//            ])->execute();

            return true;
        } else {
            return false;
        }
    }

    //注意$this->type是更新后的值
	public function getDir(){
		$path = '/home/file/pic/' . $this->type . '/' . date("Ym") . '/';

		if (!file_exists($path)){   //判断该目录是否存在  
      		if (false == mkdir($path, 0777, true)){ //第三个参数 ture
				die('make file save path :' . $path . 'fail!');
			}
        }
		
		return $path;
	}

	public function updatePic()
    {
        if ($this->validate()){
            $oldType = $this->type;
            $newType = Yii::$app->request->post('PicInfo')['type'];

            if ($oldType != $newType){

                //update url
                $delimiter = '/' . $oldType . '/';
                $tmpArray = explode($delimiter, $this->url);
                $this->url = $tmpArray[0] . '/' . $newType . '/' . $tmpArray[1];


                //先移动文件再$this->save();保存到数据库，防止移动文件失败却修改了数据库

                //确保目录存在, $this->type 是新值
                $this->type = $newType;
                $this->getDir();

                $newPath = '/home/file/pic/' . $newType . '/' . $tmpArray[1];

                if (false == rename($this->path, $newPath)){
                    return false;
                }

                $this->path = $newPath;
            }

//            //other way to update
//            Yii::$app->db->createCommand()->update('pic_info',
//                array(
//                    'type' => $newType,
//                ),
//                'id=:id',
//                array(':id'=>$id)
//            )->execute();

            $this->m_time = time();
            $this->name = Yii::$app->request->post('PicInfo')['name'];

            $this->save(false);

            return true;
        }else{
            return false;
        }
    }

    public function deletePic($id){
	    $this->status = false;
	    $this->save(false);
    }
}
