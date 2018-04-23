<?php

namespace app\modules\cms\controllers;

use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\Controller;
use app\modules\cms\models\{
    News,
    NewsSearch
};
use yii\web\{
    HttpException,
    UploadedFile
};

/**
 * Контроллер для работы с новостями.
 */
class NewsController extends Controller
{
    /**
     * Список новостей.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $this->view->title = 'Список новостей';
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());

        return $this->render(
            'index',
            [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel
            ]
        );
    }

    /**
     * Создание новости.
     *
     * @return string
     */
    public function actionCreate()
    {
        $model = new News();
        $model->date = date('Y-m-d');
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $model->image = UploadedFile::getInstance($model, 'image');
            if ($model->save(false)) {
                $this->redirect(Url::to(['/cms/news']));
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
     * Редактирование новости.
     *
     * @param null $id
     * @return string
     * @throws HttpException
     * @throws \HttpException
     */
    public function actionUpdate($id = null)
    {
        $this->view->title = 'Редактирование новости';

        if (!$id)
            throw new HttpException(404, 'Страница не найдена');

        $model = News::findOne($id);

        if (!$model)
            throw new HttpException(404, 'Запись не найдена');

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $newImage = UploadedFile::getInstance($model, 'image');
            if (!empty($newImage)) {
                $model->image = $newImage;
            }
            if ($model->save(false)) {
                $this->redirect(Url::to(['/cms/news']));
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
     * Просмотр новости.
     *
     * @param null $id
     * @return string
     * @throws HttpException
     * @throws \HttpException
     */
    public function actionView($id = null): string
    {
        if (!$id)
            throw new HttpException(404, 'Страница не найдена');

        $model = News::findOne($id);

        if (!$model)
            throw new \HttpException(404, 'Запись не найдена');

        return $this->render(
            'view',
            [
                'model' => $model
            ]
        );
    }

    /**
     * Удаление новости.
     *
     * @param $id
     * @throws HttpException
     */
    public function actionDelete($id = null) {
        if (!$id)
            throw new HttpException(404, 'Страница не найдена');

        News::deleteAll(['id' => $id]);
    }
}