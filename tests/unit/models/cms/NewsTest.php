<?php

use app\modules\cms\models\News;
use app\tests\models\NotifyBehaviorTest;
use yii\web\UploadedFile;

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
            'slug' => 'test',
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
            'slug' => 'test-update',
            'description' => 'Test Test Update',
            'content' => 'Test Test Test Update',
            'date' => '2018-08-06',
            'act' => false
        ]);

        // возвращаем обратно
        $news->attributes = [
            'name' => '11',
            'slug' => null,
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
     * Тестирование генерации slug при создании/изменении новости.
     */
    public function testGenerateSlug(): void
    {
        $news = new News();
        $news->name = 'Привет!';
        $news->save();
        $this->tester->seeRecord(News::class, [
            'name' => 'Привет!',
            'slug' => 'privet'
        ]);
        //добавляем новость с таким же адресом
        $newsTwo = new News();
        $newsTwo->name = 'Привет!';
        $newsTwo->description = 'testGenerateSlug';
        $newsTwo->save();
        $this->tester->dontSeeRecord(News::class, [
            'name' => 'Привет!',
            'slug' => 'privet',
            'description' => 'testGenerateSlug'
        ]);
        //обновляем запись
        $news->name = 'Привет Update';
        $news->save();
        $this->tester->seeRecord(News::class, [
            'name' => 'Привет Update',
            'slug' => 'privet-update'
        ]);

        $news->delete();
        $newsTwo->delete();
    }

    /**
     * Проверка загрузки изображения.
     */
    public function testNewsImage(): void
    {
        $news = new News([
            'name' => 'Тест Image',
        ]);
        $uploadedFile = $this->getMockBuilder(UploadedFile::class)
            ->setMethods(['saveAs'])
            ->getMock();
        $uploadedFile->expects($this->once())->method('saveAs')->will(
            $this->returnCallback(
                function($file) use($uploadedFile) {
                    return copy($uploadedFile->tempName, \Yii::$app->basePath . '/web/' . $file);
                }
            )
        );
        /* @var $uploadedFile UploadedFile */
        $uploadedFile->name = '1.jpg';
        $uploadedFile->type = 'jpg';
        $uploadedFile->error = 'UPLOAD_ERR_OK';
        $uploadedFile->size = filesize(codecept_data_dir( 'upload/1.jpg'));
        $uploadedFile->tempName = codecept_data_dir( 'upload/1.jpg');

        $news->image = $uploadedFile;

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
