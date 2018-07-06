<?php

namespace tests\functional\cms;

use app\modules\cms\models\News;

/**
 * Тестирование новостей в СУ.
 */
class NewsCest {

    /**
     * Доступность страницы для неавторизованного пользователя.
     *
     * @param FunctionalTester $I
     */
    public function testIndexGuest(\FunctionalTester $I): void
    {
        $I->amOnPage(['cms/news']);
        $I->seeCurrentUrlEquals('/');
    }

    /**
     * Доступность страницы для авторизованного пользователя.
     *
     * @param FunctionalTester $I
     */
    public function testIndexUser(\FunctionalTester $I): void
    {
        $I->amOnPage(['login']);
        $I->fillField('LoginForm[username]', 'demo');
        $I->fillField('LoginForm[password]', 'demo');
        $I->click('login-button');
        $I->amOnPage(['cms/news']);
        $I->seeCurrentUrlEquals('/');
    }

    /**
     * Доступность страницы для администратора.
     *
     * @param FunctionalTester $I
     */
    public function testIndexAdmin(\FunctionalTester $I): void
    {
        $I->amOnPage(['login']);
        $I->fillField('LoginForm[username]', 'admin');
        $I->fillField('LoginForm[password]', 'admin');
        $I->click('login-button');
        $I->amOnPage(['cms/news']);
        $I->seeCurrentUrlEquals('/cms/news');
    }

    /**
     * Создание новости.
     *
     * @param FunctionalTester $I
     */
    public function testCreate(\FunctionalTester $I): void
    {
        $I->amOnPage(['site/login']);
        $I->fillField('LoginForm[username]', 'admin');
        $I->fillField('LoginForm[password]', 'admin');
        $I->click('login-button');

        $I->amOnPage(['cms/news/create']);
        $I->fillField('News[name]', 'FunctionalTestName');
        $I->fillField('News[description]', 'FunctionalTestDescription');
        $I->fillField('News[content]', 'FunctionalTestContent');
        $I->fillField('News[date]', '2018-05-19');
        $I->attachFile('input[type=file]', 'upload/1.jpg');
        $I->checkOption('#news-act');
        $I->click('contact-button');

        $I->seeRecord(News::class, [
            'name' => 'FunctionalTestName',
            'description' => 'FunctionalTestDescription',
            'content' => 'FunctionalTestContent',
            'date' => '2018-05-19',
            'image' => '1.jpg',
            'act' => 1
        ]);

        //возвращаем обратно
        News::deleteAll(['name' => 'FunctionalTestName', 'description' => 'FunctionalTestDescription']);
//        unlink(\Yii::$app->basePath . '/web/uploads/1.jpg');
    }

    /**
     * Изменение новости.
     *
     * @param FunctionalTester $I
     */
    public function testUpdate(\FunctionalTester $I): void
    {
        $I->amOnPage(['site/login']);
        $I->fillField('LoginForm[username]', 'admin');
        $I->fillField('LoginForm[password]', 'admin');
        $I->click('login-button');

        $I->amOnPage(['cms/news/update', 'id' => 36]);
        $I->fillField('News[name]', 'FunctionalTestName');
        $I->fillField('News[description]', 'FunctionalTestDescription');
        $I->fillField('News[content]', 'FunctionalTestContent');
        $I->fillField('News[date]', '2018-05-19');
        $I->click('contact-button');

        $I->seeRecord(News::class, [
            'name' => 'FunctionalTestName',
            'description' => 'FunctionalTestDescription',
            'content' => 'FunctionalTestContent',
            'date' => '2018-05-19'
        ]);
        //возвращаем обратно
        News::updateAll([
            'name' => 'Test',
            'description' => 'Test Test',
            'content' => 'Test Test Test',
            'date' => '2018-04-19'
        ], ['id' => 36]);
    }

    /**
     * Просмотр новости.
     *
     * @param FunctionalTester $I
     */
    public function testView(\FunctionalTester $I): void
    {
        $I->amOnPage(['site/login']);
        $I->fillField('LoginForm[username]', 'admin');
        $I->fillField('LoginForm[password]', 'admin');
        $I->click('login-button');

        $I->amOnPage(['cms/news/view', 'id' =>36]);
        $I->see('Просмотр новости № 36', 'h1');
        $I->see('Test Test Test');
        $I->see('2018-04-19');
        $I->see('hqdefault.jpg');
    }
}
