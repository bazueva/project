<?php

namespace app\modules\cms;

/**
 * Модуль для управления контентом.
 */
class CmsModule extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\cms\controllers';

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        if (\Yii::$app->user->identity->username == 'admin') {
            return true;
        } else {
            \Yii::$app->getResponse()->redirect(\Yii::$app->getHomeUrl());
        }
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }
}
