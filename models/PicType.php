<?php

namespace app\models;

use Yii;

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

            $rows = Yii::$app->db->createCommand('SELECT type , count(type) FROM pic_info GROUP by type')->query();

            foreach($rows as $index => $typeAndNum){
                $this->_numOfPic[$typeAndNum['type']] = $typeAndNum['count(type)'];
            }
        }

        return $this->_numOfPic;
    }
}
