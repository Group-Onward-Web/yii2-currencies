<?php

namespace jarrus90\Currencies\Models;

use Yii;
use yii\helpers\Url;
use yii\db\ActiveRecord;

class Currency extends ActiveRecord {


    /**
     * Validation rules
     * @return array
     */
    public function rules() {
        return [
            'required' => [['code', 'rate'], 'required', 'on' => ['create', 'update']],
            'safe' => [['symbol', 'name'], 'safe'],
            'boolean' => [['is_default', 'is_active'], 'boolean'],
            'safeSearch' => [['code', 'rate', 'is_default', 'is_active'], 'safe', 'on' => ['search']],
        ];
    }

    public function scenarios() {
        return [
            'create' => ['code', 'symbol', 'name', 'rate', 'is_default', 'is_active'],
            'update' => ['code', 'symbol', 'name', 'rate', 'is_default', 'is_active'],
            'search' => ['code', 'symbol', 'name', 'rate', 'is_default', 'is_active'],
        ];
    }
    
    /**
     * Attribute labels
     * @return array
     */
    public function attributeLabels() {
        return [
            'code' => Yii::t('currencies', 'Code'),
            'name' => Yii::t('currencies', 'Name'),
            'symbol' => Yii::t('currencies', 'Symbol'),
            'rate' => Yii::t('currencies', 'Rate'),
            'is_default' => Yii::t('currencies', 'Default'),
            'is_active' => Yii::t('currencies', 'Active'),
        ];
    }

    /**
     * Table name
     * @return string
     */
    public static function tableName() {
        return '{{%system_currency}}';
    }

    /**
     * Search using provided params
     * @param array $params
     * @return \yii\data\ActiveDataProvider
     */
    public function search($params) {
        $query = self::find();
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        if (($this->load($params) && $this->validate())) {

        }
        return $dataProvider;
    }

    public static function getCurrency($code) {
        return self::getDb()->cache(function ($db) use ($code) {
                    return self::find()->where(['code' => $code])->asArray()->one();
                });
    }

}
