<?php

use app\modules\cms\models\News;
use app\tests\models\NotifyBehaviorTest;

/**
 * Тестирование новостей в СУ.
 */
class NewsTest extends \Codeception\Test\Unit
{
    /**
     * Валидация полей новости.
     */
    public function testNewsValidate(): void
    {
        $news = new News([
            'name' => 'Test',
            'description' => 'Test Test',
            'content' => 'Test Test Test',
            'date' => '2018-05-06',
            'act' => true
        ]);
        $this->assertTrue($news->validate(), 'model is valid');

        $news = new News([
            'description' => 'Test Test',
            'content' => 'Test Test Test',
            'date' => '2018-05-06',
            'act' => true
        ]);

        $this->assertFalse($news->validate(), 'model is not valid');
        $this->assertArrayHasKey('name', $news->getErrors(), 'name not empty');

        $news = new News([
            'name' => 'Test',
            'date' => '563434',
            'act' => '232323',
        ]);
        $this->assertFalse($news->validate(), 'model is not valid');
        $this->assertArrayHasKey('date', $news->getErrors(), 'date wrong format');
        $this->assertArrayHasKey('act', $news->getErrors(), 'must be equal to 1 or 0');
    }

    /**
     * Сохранение новости.
     */
    public function testNewsSave(): void
    {
        $news = new News([
            'name' => 'Test',
            'description' => 'Test Test',
            'content' => 'Test Test Test',
            'act' => true,
            'date' => '2018-05-06'
        ]);
        $news->detachBehavior('notifyBehavior');
        $this->assertTrue($news->save());
        $this->tester->seeRecord(News::class, [
            'name' => 'Test',
            'description' => 'Test Test',
            'content' => 'Test Test Test',
            'act' => 1,
            'date' => '2018-05-06'
        ]);
        $news->delete();
        $this->tester->dontSeeRecord(News::class, [
                'name' => 'Test',
                'description' => 'Test Test',
                'content' => 'Test Test Test',
                'act' => 1,
                'date' => '2018-05-06'
            ]
        );
    }

    /**
     * Обновление новости.
     */
    public function testNewsUpdate(): void
    {
        $news = News::findOne(8);
        $news->attributes = [
            'name' => 'Test Update',
            'description' => 'Test Test Update',
            'content' => 'Test Test Test Update',
            'date' => '2018-08-06',
            'act' => false
        ];
        $this->assertTrue($news->save());
        $this->tester->seeRecord(News::class, [
            'name' => 'Test Update',
            'description' => 'Test Test Update',
            'content' => 'Test Test Test Update',
            'date' => '2018-08-06',
            'act' => false
        ]);

        // возвращаем обратно
        $news->attributes = [
            'name' => '11',
            'description' => '<p>11</p>',
            'content' => '<p>11</p>',
            'date' => '2018-04-21',
            'act' => true
        ];
        $news->save();
    }

    /**
     * Тестирование поведения при создании новости.
     */
    public function testNotifyBehavior(): void
    {
        $news = new News();
        $notifyBehaviorTest = new NotifyBehaviorTest();
        $news->detachBehavior('notifyBehavior');
        $news->attachBehavior('notifyBehaviorTest', $notifyBehaviorTest);
        $news->save(false);
        $this->assertTrue($notifyBehaviorTest->isCall);

        // возвращаем обратно
        $news->delete();
    }

    /**
     * Проверка загрузки изображения.
     */
    public function testNewsImage(): void
    {
        $news = new News([
            'name' => 'Тест Image',
        ]);
        $file = new \yii\web\UploadedFile([
            'name'     => '1.jpg',
            'type'     => 'jpg',
            'error'    => UPLOAD_ERR_OK,
            'size'     => filesize(codecept_data_dir( 'upload/1.jpg')),
            'tempName' => codecept_data_dir( 'upload/1.jpg'),
        ]);
        $news->image = $file;

        $news->detachBehavior('notifyBehavior');
        $news->save();
        //проверяем сохранился ли файл в нужном месте
        $this->assertFileExists(\Yii::$app->basePath . '/web/uploads/' . $news->image);
        $this->tester->seeRecord(News::class, [
            'name' => 'Тест Image',
            'image' => '1.jpg'
        ]);
        //возвращаем обратно
        unlink(\Yii::$app->basePath . '/web/uploads/' . $news->image);
        $news->delete();
    }
}
