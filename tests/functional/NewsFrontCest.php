<?php

use app\tests\_pages\NewsPage;
use app\modules\cms\models\News;

/**
 * Тестирование новостей на фронтенде.
 */
class NewsFrontCest
{
    /**
     * Проверка списка новостей.
     *
     * @param FunctionalTester $I
     */
    public function testNewsIndex(\FunctionalTester $I): void
    {
        $I->amOnPage(['news']);
        $I->see('Новости', 'h1');
        $I->seeLink('Тест rocket', '/news/test-rocket', NewsPage::$blockNewsSelector);
        $I->see('10.05.2018', NewsPage::$blockNewsSelector);
        $I->see('Описание новости', NewsPage::$blockNewsSelector);
        $I->seeLink('Читать', '/news/test-rocket', NewsPage::$blockNewsSelector);
    }

    /**
     * Проверка страницы новости.
     *
     * @param FunctionalTester $I
     */
    public function testNewsView(\FunctionalTester $I): void
    {
        $I->amOnPage(['news/test-rocket']);
        $I->see('Тест rocket', 'h1');
        $I->see('10.05.2018', NewsPage::$newsDateNewsDetailSelector);
        $I->see('Полное описание новости', NewsPage::$contentNewsDetailSelector);
        $I->seeElement('img', ['src' => 'runtime/thumbnail/df/df7fef9311c733ce3b6f7b827fc0e0cc.jpg']);
    }

    /**
     * Проверка кэша.
     *
     * @param FunctionalTester $I
     * @param UnitTester $U
     */
    public function testCacheNews(\FunctionalTester $I): void
    {
        $news = News::findOne(8);

        $I->assertEquals(0, \Yii::$app->redis->exists(News::$cacheKey . 8));
        $I->amOnPage(['news/' . $news->slug]);

        $I->assertEquals(1, \Yii::$app->redis->exists(News::$cacheKey . 8));


        $news->name = 'Test';
        $news->save();
        $I->assertEquals(0, \Yii::$app->redis->exists(News::$cacheKey . 8));

        //возвращаем обратно
        $news->name = 'Тест rocket';
        $news->save();
        \Yii::$app->redis->del(News::$cacheKey . 8);
    }
}
