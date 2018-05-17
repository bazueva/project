<?php

use app\tests\_pages\ContactFormPage;

class ContactFormCest 
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnPage(['site/contact']);
    }

    public function openContactPage(\FunctionalTester $I)
    {
        $I->see('Contact', 'h1');        
    }

    public function submitEmptyForm(\FunctionalTester $I)
    {
        $I->submitForm(ContactFormPage::$formSelector, []);
        $I->expectTo('see validations errors');
        $I->see('Contact', 'h1');
        $I->see('Необходимо заполнить «Name»');
        $I->see('Необходимо заполнить «Email»');
        $I->see('Необходимо заполнить «Subject»');
        $I->see('Необходимо заполнить «Body»');
        $I->see('Неправильный проверочный код');
    }

    public function submitFormWithIncorrectEmail(\FunctionalTester $I)
    {
        $I->submitForm(ContactFormPage::$formSelector, [
            'ContactForm[name]' => 'tester',
            'ContactForm[email]' => 'tester.email',
            'ContactForm[subject]' => 'test subject',
            'ContactForm[body]' => 'test content',
            'ContactForm[verifyCode]' => 'testme',
        ]);
        $I->expectTo('see that email address is wrong');
        $I->dontSee('Необходимо заполнить «Name»', ContactFormPage::$formErrorSelector);
        $I->see('Значение «Email» не является правильным email адресом');
        $I->dontSee('Необходимо заполнить «Subject»', ContactFormPage::$formErrorSelector);
        $I->dontSee('Необходимо заполнить «Body»', ContactFormPage::$formErrorSelector);
        $I->dontSee('Неправильный проверочный код', ContactFormPage::$formErrorSelector);
    }

    public function submitFormSuccessfully(\FunctionalTester $I)
    {
        $I->submitForm(ContactFormPage::$formSelector, [
            'ContactForm[name]' => 'tester',
            'ContactForm[email]' => 'tester@example.com',
            'ContactForm[subject]' => 'test subject',
            'ContactForm[body]' => 'test content',
            'ContactForm[verifyCode]' => 'testme',
        ]);
        $I->seeEmailIsSent();
        $I->dontSeeElement(ContactFormPage::$formSelector);
        $I->see('Thank you for contacting us. We will respond to you as soon as possible.');        
    }
}
