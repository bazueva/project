<?php

use app\tests\_pages\NewsPage;

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
}
