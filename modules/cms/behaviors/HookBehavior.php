<?php

namespace app\modules\cms\behaviors;

use app\modules\cms\components\HookRocketManager;
use yii\db\ActiveRecord;

/**
 * Поведение для отправки сообщения.
 */
class HookBehavior extends \yii\base\Behavior
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
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert'
        ];
    }

    /**
     * @inheritdoc
     */
    public function afterInsert(): void
    {
        $hookRocket = new HookRocketManager(
            [
                'text' => $this->text
            ]
        );
        $hookRocket->sendMessage($this->owner);
    }
}
