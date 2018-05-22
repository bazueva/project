<?php

use app\modules\cms\models\Settings;

/**
 * Тестирование настроек.
 */
class SettingsCest
{
    /**
     * Доступность страницы для неавторизованного пользователя.
     *
     * @param FunctionalTester $I
     */
    public function testIndexGuest(\FunctionalTester $I): void
    {
        $I->amOnPage(['cms/settings']);
        $I->seeCurrentUrlEquals('/');
    }

    /**
     * Доступность страницы для авторизованного пользователя.
     *
     * @param FunctionalTester $I
     */
    public function testIndexUser(\FunctionalTester $I): void
    {
        $I->amOnPage(['site/login']);
        $I->fillField('LoginForm[username]', 'demo');
        $I->fillField('LoginForm[password]', 'demo');
        $I->click('login-button');
        $I->amOnPage(['cms/settings']);
        $I->seeCurrentUrlEquals('/');
    }

    /**
     * Доступность страницы для администратора.
     *
     * @param FunctionalTester $I
     */
    public function testIndexAdmin(\FunctionalTester $I): void
    {
        $I->amOnPage(['site/login']);
        $I->fillField('LoginForm[username]', 'admin');
        $I->fillField('LoginForm[password]', 'admin');
        $I->click('login-button');
        $I->amOnPage(['cms/settings']);
        $I->seeCurrentUrlEquals('/index-test.php?r=cms%2Fsettings');
    }

    /**
     * Создание настройки.
     *
     * @param FunctionalTester $I
     */
    public function testCreate(\FunctionalTester $I): void
    {
        $I->amOnPage(['site/login']);
        $I->fillField('LoginForm[username]', 'admin');
        $I->fillField('LoginForm[password]', 'admin');
        $I->click('login-button');

        $I->amOnPage(['cms/settings/create']);
        $I->fillField('Settings[name_setting]', 'Test');
        $I->fillField('Settings[name]', 'Test');
        $I->fillField('Settings[value]', 'Test');
        $I->click('contact-button');

        $I->seeRecord(Settings::class, [
            'name_setting' => 'Test',
            'name' => 'Test',
            'value' => 'Test',
        ]);

        //возвращаем обратно
        Settings::deleteAll(['name_setting' => 'Test', 'name' => 'Test', 'value' => 'Test']);
    }

    /**
     * Изменение настройки.
     *
     * @param FunctionalTester $I
     */
    public function testUpdate(\FunctionalTester $I): void
    {
        $I->amOnPage(['site/login']);
        $I->fillField('LoginForm[username]', 'admin');
        $I->fillField('LoginForm[password]', 'admin');
        $I->click('login-button');

        $I->amOnPage(['cms/settings/update', 'id' => 1]);
        $I->fillField('Settings[name_setting]', 'Test');
        $I->fillField('Settings[name]', 'Test');
        $I->fillField('Settings[value]', 'Test');
        $I->click('contact-button');

        $I->seeRecord(Settings::class, [
            'name_setting' => 'Test',
            'name' => 'Test',
            'value' => 'Test',
        ]);
        //возвращаем обратно
        Settings::updateAll([
            'name_setting' => 'hook_rocket_url',
            'name' => 'Адрес для RocketChat',
            'value' => 'https://rocket.sima-land.ru/hooks/2PprmXWLo4EnAm6FH/xg4QLkdHd74kf7C325Cne8MDE7LJC4pAvEJvwfjZfARrgAYi',
        ], ['id' => 1]);
    }
}
