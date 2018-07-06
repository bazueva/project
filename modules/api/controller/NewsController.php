<?php

namespace app\modules\api\controller;

use app\modules\cms\models\News;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;

/**
 * Новости.
 *
 * Доступные опции($_GET):
 * act=1 - активные новости
 * date_from = 2018-05-28 - дата от
 * date_to = 2018-05-28 - дата до
 * Сортировка по всем полям: sort =_field_.
 */
class NewsController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = News::class;

    /**
     * @inheritdoc
     */
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items'
    ];

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        $actions['create']['class'] = '\app\modules\api\action\CreateActionNews';
        return $actions;
    }

    /**
     * @inheritdoc
     */
    public function prepareDataProvider(): ActiveDataProvider
    {
        $query = $this->modelClass::find();
        $params = \Yii::$app->request->get();
        if ($params['act']) {
            $query->andFilterWhere(['act' => $params['act']]);
        }
        if ($params['date_from']) {
            $query->andFilterWhere(['>', 'date', $params['date_from']]);
        }
        if ($params['date_to']) {
            $query->andFilterWhere(['<', 'date', $params['date_to']]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 32
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' =>SORT_ASC
                ]
            ]
        ]);

        return $dataProvider;
    }
}
