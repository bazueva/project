<?php

/**
 * Тестирование api новостей.
 */
class NewsCest
{
    /**
     * @var string Базовый урл.
     */
    protected $base_url = 'api/news';

    /**
     * Тестирование списка новостей.
     *
     * @param ApiTester $I Тестер
     */
    public function testIndex(ApiTester $I): void
    {
        $I->sendGET($this->base_url);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
           'items' => [
               'id' => 36,
               'name' => 'Test',
               'description' => 'Test Test',
               'content' => 'Test Test Test',
               'image' => 'hqdefault.jpg',
               'image_url' => 'http://localhost/uploads/hqdefault.jpg',
               'date' => '2018-04-19',
               'created_at' => '2018-05-03, 18:17:01',
               'updated_at' => '2018-05-03, 18:17:01',
               'act' => '1',
               'slug' => 'functionaltestname'
           ]
        ]);
    }

    /**
     * Тестирование фильтров.
     *
     * @param ApiTester $I Тестер
     */
    public function testFilters(\ApiTester $I): void
    {
        $I->sendGET($this->base_url, [
            'act' => 1,
            'date_from' => '2018-05-05'
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'items' => [
                'id' => 27,
                'name' => 'Тест rocket',
                'description' => '<p>Описание новости</p>',
                'content' => 'Полное описание новости',
                'image' => 'kittens_01.jpg',
                'image_url' => 'http://localhost/uploads/kittens_01.jpg',
                'date' => '2018-05-10',
                'created_at' => '2018-05-02, 16:59:32',
                'updated_at' => '2018-05-02, 16:59:32',
                'act' => 1,
                'slug' => 'test-rocket'
            ]
        ]);
        $I->seeHttpHeader('X-Pagination-Total-Count', 1);

        $I->sendGET($this->base_url, [
            'act' => 0,
            'date_to' => '2018-05-10'
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeHttpHeader('X-Pagination-Total-Count', 12);

        $I->sendGET($this->base_url, [
            'act' => 0,
            'date_from' => '2018-04-01',
            'date_to' => '2018-05-05'
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeHttpHeader('X-Pagination-Total-Count',9);
    }

    /**
     * Создание новости.
     *
     * @param ApiTester $I Тестер
     */
    public function testCreate(\ApiTester $I): void
    {
        $I->sendPOST($this->base_url, [
            'name' => 'ApiTest',
            'description' => 'ApiTest Description',
            'content' => 'ApiTest Content',
            'act' => 1,
            'date' => '2020-10-20'
        ], [
            'image' => [
                'name' => '1.jpg',
                 'type' => 'image/jpg',
                 'error' => UPLOAD_ERR_OK,
                 'size' => filesize(codecept_data_dir('upload/1.jpg')),
                 'tmp_name' => codecept_data_dir('upload/1.jpg')
            ]
        ]);
        $I->seeResponseCodeIs(201);
        $I->seeResponseContainsJson([
            'name' => 'ApiTest',
            'description' => 'ApiTest Description',
            'content' => 'ApiTest Content',
            'image_url' => 'http://localhost/uploads/1.jpg',
            'act' => 1,
            'date' => '2020-10-20'
        ]);
        $id = $I->grabDataFromResponseByJsonPath('$.id')[0];
        $I->sendDELETE($this->base_url . '/' . $id);
    }

    /**
     * Тестирование сортировки.
     *
     * @param ApiTester $I Тестер
     */
    public function testSort(\ApiTester $I): void
    {
        $I->sendGET($this->base_url, [
           'sort' => 'id'
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'items' => [
                0 => [
                    'id'=> 8
                ],
                1 => [
                    'id' => 9
                ]
            ]
        ]);
    }

    /**
     * Тестирование валидации.
     *
     * @param ApiTester $I Тестер
     */
    public function testValidate(\ApiTester $I): void
    {
        $I->sendPOST($this->base_url, [
            'name' => '',
            'description' => 'ApiTest Description',
            'content' => 'ApiTest Content',
            'act' => 1,
            'date' => '2020-10-20'
        ]);
        $I->seeResponseContainsJson([
            'field' => 'name',
            'message' => 'Необходимо заполнить «Название».'
        ]);
        $I->sendPOST($this->base_url, [
            'name' => ' ApiTest Description ApiTest Description ApiTest Description ApiTest Description ApiTest Description
            ApiTest Description ApiTest Description ApiTest Description ApiTest Description ApiTest Description ApiTest Description
            ApiTest DescriptionApiTest Description ApiTest Description ApiTest Description ',
            'description' => 'ApiTest Description',
            'content' => 'ApiTest Content',
            'act' => 'ty',
            'date' => '0',
            'slug' => ' ApiTest Description ApiTest Description ApiTest Description ApiTest Description ApiTest Description
            ApiTest Description ApiTest Description ApiTest Description ApiTest Description ApiTest Description ApiTest Description
            ApiTest DescriptionApiTest Description ApiTest Description ApiTest Description ',
        ]);
        $I->seeResponseContainsJson([
            [
                'field' => 'name',
                'message' => 'Значение «Название» должно содержать максимум 255 символа.'
            ],
            [
                'field' => 'slug',
                'message' => 'Значение «Адрес страницы» должно содержать максимум 255 символа.'
            ],
            [
                'field' => 'date',
                'message' => 'Неверный формат значения «Дата».'
            ],
            [
                'field' => 'act',
                'message' => 'Значение «Активность» должно быть равно «1» или «0».'
            ]
        ]);
    }

    /**
     * Изменение новости.
     *
     * @param ApiTester $I Тестер
     */
    public function testUpdate(\ApiTester $I): void
    {
        $I->sendPUT($this->base_url . '/10', [
            'name' => 'ApiTestUpdate',
            'description' => 'ApiTestUpdate',
            'content' => 'ApiTestUpdate',
            'date' => '2020-01-20',
            'act' => 1,
            'slug' => 'apitestupdate'
        ]);
        $I->seeResponseContainsJson([
            'name' => 'ApiTestUpdate',
            'description' => 'ApiTestUpdate',
            'content' => 'ApiTestUpdate',
            'date' => '2020-01-20',
            'act' => 1,
            'slug' => 'apitestupdate'
        ]);
        //возвращаем обратно
        $I->sendPUT($this->base_url . '/10', [
            'name' => 'Новая новость',
            'description' => '<p>213123123</p>',
            'content' => '<p>3123123</p>',
            'date' => '2018-04-16',
        ]);
    }

    /**
     * Удаление новости.
     *
     * @param ApiTester $I Тестер
     */
    public function testDelete(\ApiTester $I): void
    {
        $I->sendPOST($this->base_url, [
            'name' => 'ApiTestDelete',
            'description' => 'ApiTestDelete Description',
            'content' => 'ApiTestDelete Content',
            'act' => 1,
            'date' => '2020-10-20'
        ]);
        $I->seeResponseCodeIs(201);
        $id = $I->grabDataFromResponseByJsonPath('$.id')[0];
        $I->sendDELETE($this->base_url . '/' . $id);
        $I->seeResponseCodeIs(204);
        $I->sendGET($this->base_url . '/' . $id);
        $I->seeResponseCodeIs(404);
    }
}
