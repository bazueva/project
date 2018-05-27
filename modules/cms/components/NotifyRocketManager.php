<?php

namespace app\modules\cms\components;

use app\modules\cms\models\Settings;
use yii\helpers\Url;

/**
 * Отправка сообщений в RocketChat.
 */
class NotifyRocketManager extends \yii\base\Component
{
    /**
     * Имя настройки адреса в СУ.
     */
    public const NAME_SETTING_ROCKET = 'hook_rocket_url';

    /**
     * Адрес для отправки сообщения.
     *
     * @var string
     */
    public $url = 'https://rocket.sima-land.ru/hooks/2PprmXWLo4EnAm6FH/xg4QLkdHd74kf7C325Cne8MDE7LJC4pAvEJvwfjZfARrgAYi';

    /**
     * Заголовок сообщения.
     */
    public $text = 'Добавлена запись';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setUrl();
        parent::init();
    }

    /**
     * Отправка сообщения.
     *
     * @param $model object
     */
    public function sendMessage($model): void
    {
        $data_string = $this->generationRequest($model);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );
        curl_exec($ch);
        curl_close($ch);
    }

    /**
     * Формирование сообщения.
     *
     * @param $model object
     * @return string
     */
    public function generationRequest($model): string
    {
        $data = [
            'text' => $this->text
            ];
        $data['attachments'][0]['title'] = $model->name;
        $data['attachments'][0]['title_link'] = Url::to(['/news/' . $model->slug], true);
        if ($model->description) {
            $data['attachments'][0]['text'] = strip_tags($model->description);
        }
        if ($model->image && $model->image->name) {
            $data['attachments'][0]['image_url'] = Url::to($model->dirImages . $model->image->name, true);
        }
        $data_string = json_encode($data);
        return $data_string;
    }

    /**
     * Установить адрес для отправки сообщения.
     */
    public function setUrl(): void
    {
        $urlFromSetting = Settings::getValue(self::NAME_SETTING_ROCKET);
        if ($urlFromSetting) {
            $this->url = $urlFromSetting;
        }
    }
}
