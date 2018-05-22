<?php

use app\tests\_pages\MainPage;

/**
 * Тестирование главной страницы.
 */
class MainCest
{
    /**
     * Проверка меню в шапке сайта для гостя.
     *
     * @param FunctionalTester $I
     */
    public function testMainMenuGuest(\FunctionalTester $I): void
    {
        $I->amOnPage('/');
        $I->seeLink('Новости', '/index-test.php?r=news%2Findex', MainPage::$menuHeaderSelector);
        $I->dontSeeLink('Редактировать новости', '/index-test.php?r=cms%2Fnews', MainPage::$menuHeaderSelector);
        $I->dontSeeLink('Настройки', '/index-test.php?r=cms%2Fsettings', MainPage::$menuHeaderSelector);
    }

    /**
     * Проверка меню в шапке сайта для администратора.
     *
     * @param FunctionalTester $I
     */
    public function testMainMenuAdmin(\FunctionalTester $I): void
    {
        $I->amOnPage(['site/login']);
        $I->fillField('LoginForm[username]', 'admin');
        $I->fillField('LoginForm[password]', 'admin');
        $I->click('login-button');
        $I->amOnPage('/');
        $I->seeLink('Новости', '/index-test.php?r=news%2Findex', MainPage::$menuHeaderSelector);
        $I->seeLink('Редактировать новости', '/index-test.php?r=cms%2Fnews', MainPage::$menuHeaderSelector);
        $I->seeLink('Настройки', '/index-test.php?r=cms%2Fsettings', MainPage::$menuHeaderSelector);
    }

    /**
     * Проверка меню в шапке сайта для авторизованного пользователя.
     *
     * @param FunctionalTester $I
     */
    public function testMainMenuUser(\FunctionalTester $I): void
    {
        $I->amOnPage(['site/login']);
        $I->fillField('LoginForm[username]', 'demo');
        $I->fillField('LoginForm[password]', 'demo');
        $I->click('login-button');
        $I->amOnPage('/');
        $I->seeLink('Новости', '/index-test.php?r=news%2Findex', MainPage::$menuHeaderSelector);
        $I->dontSeeLink('Редактировать новости', '/index-test.php?r=cms%2Fnews', MainPage::$menuHeaderSelector);
        $I->dontSeeLink('Настройки', '/index-test.php?r=cms%2Fsettings', MainPage::$menuHeaderSelector);
    }
}
