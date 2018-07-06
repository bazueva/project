<?php

namespace app\components;

use app\modules\cms\models\News;
use yii\base\BaseObject;
use yii\web\UrlRuleInterface;

class NewsUrlManager extends BaseObject implements UrlRuleInterface
{
    /**
     * @var string Url страницы новостей.
     */
    public $urlNews = 'news';

    /**
     * @inheritdoc
     */
    public function parseRequest($manager, $request)
    {
        $pathInfo = $request->getPathInfo();
        $pathInfo = explode('/', $pathInfo);
        if ($pathInfo[0] == $this->urlNews) {
            $news = News::find()->where(['slug' => $pathInfo[1]])->one();
            if (!$news) {
                return false;
            }
            return ['news/view', ['id' => $news->id]];
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function createUrl($manager, $route, $params)
    {
        $pathInfo = explode('/', $route);
        if ($pathInfo[0] == $this->urlNews) {
            return $route;
        }

        return false;
    }
}
