<?php

use app\modules\cms\models\Settings;

/**
 * Тестирование настроек.
 */
class SettingsTest extends \Codeception\Test\Unit
{
    /**
     * Валидация полей настройки.
     */
    public function testNewsValidate(): void
    {
        $news = new Settings([
            'name_setting' => 'test',
            'name' => 'Тестовая настройка',
            'value' => 'test'
        ]);
        $this->assertTrue($news->validate(), 'model is valid');

        $news = new Settings([
            'name_setting' => 'test',
            'name' => '',
            'value' => ''
        ]);

        $this->assertFalse($news->validate(), 'model is not valid');
        $this->assertArrayHasKey('name', $news->getErrors(), 'name not empty');
    }

    /**
     * Создание настройки.
     */
    public function testCreate(): void
    {
        $setting = new Settings([
            'name' => 'Test',
            'name_setting' => 'Test',
            'value' => 'Test'
        ]);
        $setting->save();
        $this->tester->seeRecord(Settings::class, [
            'name' => 'Test',
            'name_setting' => 'Test',
            'value' => 'Test'
        ]);
        $setting->delete();
        $this->tester->dontSeeRecord(Settings::class, [
                'name' => 'Test',
                'name_setting' => 'Test',
                'value' => 'Test'
            ]
        );
    }
}
