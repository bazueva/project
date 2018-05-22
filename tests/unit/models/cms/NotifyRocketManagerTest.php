<?php

use app\modules\cms\components\NotifyRocketManager;
use app\modules\cms\models\Settings;
use app\modules\cms\models\News;

/**
 * Тестирование компонента NotifyRocketManager.
 */
class NotifyRocketManagerTest extends \Codeception\Test\Unit
{
    /**
     * Тестирование установки адреса для запроса.
     */
    public function testSetUrl(): void
    {
        //передаем урл в компонент
        $notifyRocketManager = new NotifyRocketManager([
            'url' => 'http://vk.com'
        ]);
        //url не изменился
        $this->assertEquals(
            'https://rocket.sima-land.ru/hooks/2PprmXWLo4EnAm6FH/xg4QLkdHd74kf7C325Cne8MDE7LJC4pAvEJvwfjZfARrgAYi',
            $notifyRocketManager->url
        );
        // поменяем в Settings название настройки, url должен остаться таким же
        Settings::updateAll(['name_setting' => 'rocket'], ['id' => 1]);
        $notifyRocketManager->setUrl();
        $this->assertEquals(
            'https://rocket.sima-land.ru/hooks/2PprmXWLo4EnAm6FH/xg4QLkdHd74kf7C325Cne8MDE7LJC4pAvEJvwfjZfARrgAYi',
            $notifyRocketManager->url
        );

        // вернем обратно
        Settings::updateAll(['name_setting' => 'hook_rocket_url'], ['id' => 1]);
    }

    /**
     * Тестирование формирования сообщения.
     */
    public function testGenerationRequest(): void
    {
        $notifyRocketManager = new NotifyRocketManager();
        $notifyRocketManager->text = 'Example message';
        $file = new \yii\web\UploadedFile([
            'name'     => '1.jpg',
            'type'     => 'jpg',
            'error'    => UPLOAD_ERR_OK,
            'size'     => filesize(codecept_data_dir( 'upload/1.jpg')),
            'tempName' => codecept_data_dir( 'upload/1.jpg'),
        ]);
        $model = new News([
            'id' => 24,
            'name' => 'Rocket.Chat',
            'description' => 'Test',
            'image' => $file,
            'dirImages' => '_tests/data/upload/'
        ]);
        $this->assertEquals(
            '{"text":"Example message","attachments":[{"title":"Rocket.Chat","title_link":"http:\/\/localhost\/index-test.php?r=news%2Fview&id=24","text":"Test","image_url":"http:\/\/localhost\/_tests\/data\/upload\/1.jpg"}]}',
            $notifyRocketManager->generationRequest($model)
        );

        $model->image = '';
        $model->description = '';
        $this->assertEquals(
            '{"text":"Example message","attachments":[{"title":"Rocket.Chat","title_link":"http:\/\/localhost\/index-test.php?r=news%2Fview&id=24"}]}',
            $notifyRocketManager->generationRequest($model)
        );
    }
}
