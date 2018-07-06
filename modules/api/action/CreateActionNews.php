<?php

namespace app\modules\api\action;

use yii\web\UploadedFile;
use yii\helpers\Url;

/**
 * Действие для создания новости через API.
 */
class CreateActionNews extends \yii\rest\CreateAction
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        $model = new $this->modelClass();
        if ($model->load(\Yii::$app->getRequest()->getBodyParams(), '') && $model->validate()) {
            $model->image = UploadedFile::getInstanceByName("image");
            if ($model->save(false)) {
                $response = \Yii::$app->getResponse();
                $response->setStatusCode(201);
                $id = implode(',', array_values($model->getPrimaryKey(true)));
                $response->getHeaders()->set('Location', Url::toRoute(['view', 'id' => $id], true));
            } elseif (!$model->hasErrors()) {
                throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
            }
        }
        return $model;
    }
}
