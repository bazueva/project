<?php

namespace app\modules\api;

use yii\base\Module;

/**
 * Модуль API.
 */
class ApiModule extends Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\api\controller';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }
}
