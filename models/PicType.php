<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "pic_type".
 *
 * @property integer $id
 * @property string $name
 */
class PicType extends \yii\db\ActiveRecord
{
    //每种图片类型对应图片数目的数组
    private $_numOfPic;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['id', 'name'], 'unique'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '类型名称',
        ];
    }

    //return 每种图片类型对应图片数目的数组
    public function getNumOfPic(){

        if (!isset($this->_numOfPic)){

            $this->_numOfPic = array();

            $rows = Yii::$app->db->createCommand('SELECT type , count(type) FROM pic_info WHERE status = 1 GROUP by type')->query();

            foreach($rows as $index => $typeAndNum){
                $this->_numOfPic[$typeAndNum['type']] = $typeAndNum['count(type)'];
            }
        }

        return $this->_numOfPic;
    }

    /**
     * 将栏目组合成key-value形式
     */
    public static  function  get_type(){
        $cat = PicType::find()->all();
        $cat = ArrayHelper::map($cat, 'id', 'name');
        return $cat;
    }

    /**
     * 通过栏目id获得栏目名称
     * @param unknown $id
     * @return
     */

    public static  function  get_type_text($id){
        $datas = PicType::find()->all();
        $datas = ArrayHelper::map($datas, 'id', 'name');
        return  $datas[$id];
    }
}
