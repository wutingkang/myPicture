<?php

namespace app\models;

use Yii;
use app\components\ImageHelper;
use yii\data\ActiveDataProvider;

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

//    public static function getDb()
//    {
//        return Yii::$app->db_test;
//    }

    /**
     * @var save picture file
     */
    public $imageFile;
    public $dstWight;
    public $dstHeight;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
            [['type'], 'integer'],
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg', 'maxSize' => 1024*1000, 'message' => '您上传的文件过大', 'on' => ['upload']],
            [['dstWight', 'dstHeight'], 'integer', 'on' => ['upload']],

            ['dstWight', 'default', 'value' => Yii::$app->params['defaultWight'], 'on' => ['upload']], //当不在view中使用$dstWight时default才其作用
            ['dstHeight', 'default', 'value' => Yii::$app->params['defaultHeight'], 'on' => ['upload']],

            ['dstWight', 'integer', 'min' => 400, 'max' => 1600, 'integerOnly'=>true, 'on' => ['upload']], //
            ['dstHeight', 'integer', 'min'=> 225, 'max'=>900, 'integerOnly'=>true, 'on' => ['upload']],
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

    //在大多数情况下，是不需要重写 scenarios() 的，通过配置 rules() 来改变 scenarios() 的返回值，而不是直接手动覆盖 sceanrios()
//    public function scenarios()
//    {
//        return [
//            'upload' => ['imageFile', 'dstWight', 'dstHeight'],
//            'update' => ['name', 'type'],
//        ];
//    }

    //只需要在 transactions() 中声明需要事务支持的操作就足够了。 后续的怎么使声明生效的，Yii框架已经替我们写好了
    public function transactions() {
        return [
            'upload' => self::OP_INSERT,
            'update' => self::OP_UPDATE,
        ];
    }


    /**
     * 上传并裁剪图片
     * @return boolean whether the upload is successfull
     */
    public function upload()
    {
        if ($this->validate()) {

            $this->type = Yii::$app->request->post('PicInfo')['type']; //获取路径需要用到type

            if (false === ($Dir = $this->getDir())) return false;

            $saveName = date("YmdHis") . '_' . rand(100, 999) . '.' . $this->imageFile->extension;
	   
            //存入数据库,可以考虑加入事务，先存入数据库成功后再上传文件
		    $this->name = $this->imageFile->baseName;
			$this->path = $Dir . $saveName;
			$this->url = Yii::$app->params['storeUrl'] . '/' . $this->type . '/' . date("Ym") . '/' . $saveName;
			$this->time = time();
			$this->m_time = $this->time;
			$this->size = $this->imageFile->size;
			//$this->type = //上面已经赋值
			$this->status = true;


			$this->save(false); //$this->validate() 验证过了

//          other way
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


            $this->imageFile->saveAs($this->path);




            if ('' === Yii::$app->request->post('PicInfo')['dstWight']){ //不填写view中的form，返回‘’，使用isset返回true
                $this->dstWight = Yii::$app->params['defaultWight'];
            }else{
                $this->dstWight = Yii::$app->request->post('PicInfo')['dstWight'];
            }

            if ('' === Yii::$app->request->post('PicInfo')['dstHeight']){
                $this->dstHeight = Yii::$app->params['defaultHeight'];
            }else{
                $this->dstHeight = Yii::$app->request->post('PicInfo')['dstHeight'];
            }


            $img = new ImageHelper($this->path);

            //裁剪图片
            if(false === ($img->resize($Dir, $this->dstWight, $this->dstHeight))) return false;

            //上传图片
            $result = $img->uploadToRemoteFileServer($this->path, Yii::$app->params['uploadUrl'], $this->id);
            if(false === ($this->decodeResult($result))) return false;

            return true;
        } else {
            return false;
        }
    }

    /**
     * return 获取图片存储路径
     */
	public function getDir(){

		$path = Yii::$app->params['storeDir'] . '/' . $this->type . '/' . date("Ym") . '/'; //注意$this->type是更新后的值

		if (!file_exists($path)){   //判断该目录是否存在  
      		if (false === mkdir($path, 0777, true)){ //第三个参数 ture
                return false;
			}
        }
		
		return $path;
	}

    /**
     * 更新图片信息
     * @return boolean whether the update is successfull
     */
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

                $newPath = Yii::$app->params['storeUrl'] . '/' . $newType . '/' . $tmpArray[1];

                if (false === rename($this->path, $newPath)){
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

    /**
     * @param int $id
     * 删除图片，只改变数据库中status字段，并不真的删除
     */
    public function deletePic($id){
	    $this->status = false;
	    $this->save(false);
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = PicInfo::find()->where("status != :deleteStatus", [":deleteStatus" => false]); //只显示未删除的图片

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
                'pagesize'=> 3
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'time' => $this->time,
            'm_time' => $this->m_time,
            'size' => $this->size,
            'type' => $this->type,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'path', $this->path])
            ->andFilterWhere(['like', 'url', $this->url]);

        return $dataProvider;
    }

    public function decodeResult($result){
        if($resultArray = json_decode($result, true)){

            if (0 == $resultArray['status']){

                \Yii::$app->db->createCommand()->update('pic_info',
                    array(
                        'downloadUrl' => $resultArray['data']['key'],
                    ),
                    'id=:id',
                    array(':id'=>$this->id)
                )->execute();

                return true;
            }
//            elseif(1000 == $resultArray['status']){
//                die('参数错误');
//            }elseif(2001 == $resultArray['status']){
//                die('服务器出错');
//            }elseif(1001 == $resultArray['status']){
//                die('上传失败');
//            }else{
//                die('unknow status error');
//            }
        }

        return false;
    }
}
