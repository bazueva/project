<?php

namespace app\tests\models;

/**
 * Модель поведения для теста.
 */
class NotifyBehaviorTest extends \app\modules\cms\behaviors\NotifyBehavior
{
    /**
     * @var bool Метод sendMessageToRocketChat был вызван.
     */
    public $isCall = false;

    public function sendMessageToRocketChat(): void
    {
        $this->isCall = true;
    }
}
