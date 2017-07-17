<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;

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
            [['id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique' ,'on' => 'create'],
            [['name'], 'required' ,'on' => 'create'],
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

//    /**
//     * @inheritdoc
//     */
//    public function scenarios()
//    {
//        $parent_scenarios = parent::scenarios();//继承父类的场景
//
//        $self_scenarios = [
//            'create' => ['name', 'id'],
//        ];
//
//        return array_merge($parent_scenarios, $self_scenarios); //合并场景
//    }

    /**
     * return 每种图片类型对应图片数目的数组
     */
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
     *  @return array[id => name]
     */
    public static  function  getTypes(){
        $types = PicType::find()->all();
        $types = ArrayHelper::map($types, 'id', 'name');
        return $types;
    }

    /**
     * 通过栏目id获得栏目名称
     * @param  $id
     * @return string $name
     */
    public static  function  getTypeName($id){
        $types = PicType::find()->all();
        $types = ArrayHelper::map($types, 'id', 'name');
        return  $types[$id];
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
        $query = PicType::find();

        // add conditions that should always apply here

        //$query->having("id != :defaultId", array(":defaultId" => 0)); //不隐藏默认分类

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pagesize' => 8
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
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
