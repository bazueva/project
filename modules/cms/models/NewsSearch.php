<?php

namespace app\modules\cms\models;

use app\modules\cms\models\News;
use yii\data\ActiveDataProvider;

/**
 * Модель для поиска по новостям.
 */
class NewsSearch extends News
{
    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [
                'id',
                'integer'
            ],
            [
                'name',
                'string'
            ]
        ];
    }

    /**
     * Поиск по новостям.
     *
     * @param $params array
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = News::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20
            ],
            'sort' => [
                'defaultOrder' =>  [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        if (!$this->load($params) && !$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}