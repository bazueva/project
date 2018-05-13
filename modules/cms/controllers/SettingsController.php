<?php

namespace app\modules\cms\controllers;

use app\modules\cms\models\Settings;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\helpers\Url;

/**
 * Настройки приложения.
 */
class SettingsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actionIndex(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Settings::find(),
            'pagination' => [
                'pageSize' => 20
            ],
            'sort' => [
                'defaultOrder' =>  [
                    'id' => SORT_DESC
                ]
            ]
        ]);

        return $this->render(
            'index',
            [
                'dataProvider' => $dataProvider
            ]
        );
    }

    /**
     * Создание настройки.
     *
     * @return string
     */
    public function actionCreate(): string
    {
        $model = new Settings();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->save(false)) {
                $this->redirect(Url::to(['/cms/settings']));
            }
        }
        return $this->render(
            'create',
            [
                'model' => $model
            ]
        );
    }

    /**
     * Редактирование настройки.
     *
     * @param null $id
     * @return string
     * @throws HttpException
     * @throws \HttpException
     */
    public function actionUpdate($id = null): string
    {
        $this->view->title = 'Редактирование настройки';

        if (!$id)
            throw new HttpException(404, 'Страница не найдена');

        $model = Settings::findOne($id);

        if (!$model)
            throw new HttpException(404, 'Запись не найдена');

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->save(false)) {
                $this->redirect(Url::to(['/cms/settings']));
            }
        }

        return $this->render(
            'create',
            [
                'model' => $model
            ]
        );
    }
}
