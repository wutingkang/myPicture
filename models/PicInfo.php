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
            [['name', 'path', 'url', 'time', 'm_time', 'size', 'type', 'status'], 'required'],
            [['time', 'm_time'], 'safe'],
            [['size'], 'number'],
            [['type', 'status'], 'integer'],
            [['name', 'url'], 'string', 'max' => 30],
            [['path'], 'string', 'max' => 50],
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
    {
        if ($this->validate()) {
            $this->imageFile->saveAs('/home/file/pic/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);

            //存入数据库
            Yii::$app->db->createCommand()->insert('pic_info', [
                'name' => $this->name,
                'path' => Yii::getAlias('@web/uploads/user/') . $this->created_by . '/' . $info['name'], //存储路径
                'store_name' => $info['name'], //保存的名称
                'album_id' => $this->id,
                'created_at' => time(),
                'created_by' => Yii::$app->user->id,
            ])->execute();

            return true;
        } else {
            return false;
        }
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
