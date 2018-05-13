<?php

namespace app\modules\cms\models;

use yii\db\ActiveRecord;

/**
 * Настройки приложения.
 *
 * @property $id            Идентификатор настройки
 * @property $name_setting  Имя настройки
 * @property $name          Название настройки
 * @property $value         Значение
 */
class Settings extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return 'settings';
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [
                ['name_setting', 'name', 'value'],
                'required'
            ],
            [
                ['name_setting', 'name', 'value'],
                'string'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'name_setting' => 'Идентификатор настройки',
            'name' => 'Название настройки',
            'value' => 'Значение'
        ];
    }

    /**
     * Получение значения настройки по имени настройки.
     *
     * @var string
     * @return string
     */
    public static function getValue(string $name_setting): string
    {
        $setting = Settings::find(['name_setting' => $name_setting])->select('value')->one();
        return $setting->value;
    }
}
