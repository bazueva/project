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
        $I->seeLink('Новости', '/news', MainPage::$menuHeaderSelector);
        $I->click('Новости');
        $I->dontSeeLink('Редактировать новости', '/cms/news', MainPage::$menuHeaderSelector);
        $I->dontSeeLink('Настройки', '/cms/settings', MainPage::$menuHeaderSelector);
    }

    /**
     * Проверка меню в шапке сайта для администратора.
     *
     * @param FunctionalTester $I
     */
    public function testMainMenuAdmin(\FunctionalTester $I): void
    {
        $I->amOnPage(['login']);
        $I->fillField('LoginForm[username]', 'admin');
        $I->fillField('LoginForm[password]', 'admin');
        $I->click('login-button');
        $I->amOnPage('/');
        $I->seeLink('Новости', '/news', MainPage::$menuHeaderSelector);
        $I->seeLink('Редактировать новости', '/cms/news', MainPage::$menuHeaderSelector);
        $I->seeLink('Настройки', '/cms/settings', MainPage::$menuHeaderSelector);
    }

    /**
     * Проверка меню в шапке сайта для авторизованного пользователя.
     *
     * @param FunctionalTester $I
     */
    public function testMainMenuUser(\FunctionalTester $I): void
    {
        $I->amOnPage(['login']);
        $I->fillField('LoginForm[username]', 'demo');
        $I->fillField('LoginForm[password]', 'demo');
        $I->click('login-button');
        $I->amOnPage('/');
        $I->seeLink('Новости', '/news', MainPage::$menuHeaderSelector);
        $I->dontSeeLink('Редактировать новости', '/cms/news', MainPage::$menuHeaderSelector);
        $I->dontSeeLink('Настройки', '/cms/settings', MainPage::$menuHeaderSelector);
    }
}
