<?php

namespace app\tests\_pages;

/**
 * Селекторы для страницы Контакты.
 */
class ContactFormPage
{
    /**
     * @var string Селектор для вывода ошибок в форме.
     */
    public static $formErrorSelector = '.help-inline';

    /**
     * @var string Селектор формы.
     */
    public static $formSelector = '#contact-form';
}
