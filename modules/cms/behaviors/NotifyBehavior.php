<?php

namespace app\modules\cms\behaviors;

use yii\db\ActiveRecord;

/**
 * Поведение для отправки сообщения.
 */
class NotifyBehavior extends \yii\base\Behavior
{
    /**
     * Заголовок сообщения.
     *
     * @var string
     */
    public $text = 'Добавлена запись';

    /**
     * @inheritdoc
     */
    public function events(): array
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'sendMessageToRocketChat'
        ];
    }

    /**
     * Отправка сообщения в RocketChat.
     */
    public function sendMessageToRocketChat(): void
    {
        \Yii::$app->notifyRocketManager->text = $this->text;
        \Yii::$app->notifyRocketManager->sendMessage($this->owner);
    }
}
