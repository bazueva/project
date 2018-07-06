<?php

namespace app\controllers;

use app\modules\cms\models\News;
use yii\data\ActiveDataProvider;
use yii\web\{
    Controller,
    HttpException
};

/**
 * Новости.
 */
class NewsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => 'yii\filters\PageCache',
                'only' => ['index, view'],
                'duration' => 3600,
            ],
        ];
    }

    /**
     * Список новостей.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => News::find()->where(['act' => 1]),
            'pagination' => [
                'pageSize' => 10
            ],
            'sort' => [
                'defaultOrder' => [
                    'date' => SORT_DESC
                ]
            ]
        ]);

        $this->view->title = 'Новости';
        return $this->render(
            'index',
            [
                'dataProvider' => $dataProvider
            ]
        );
    }

    /**
     * Страница новости.
     *
     * @param $id
     * @return string
     * @throws HttpException
     */
    public function actionView($id)
    {
        if (!$id)
            throw new HttpException(404, 'Страница не найдена');

        if (!$model = \Yii::$app->redis->get(News::$cacheKey . $id)) {
            $model = News::findOne($id);
            if ($model) {
                \Yii::$app->redis->set(News::$cacheKey . $id, serialize($model));
            }
        } else {
            $model = unserialize($model);
        }

        if (!$model) {
            throw new HttpException(404, 'Страница не найдена');
        }

        $this->view->title = $model->name;

        return $this->render(
            'detail',
            [
                'model' => $model
            ]
        );
    }
}