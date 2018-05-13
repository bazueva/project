<?php

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
        $I->submitForm('#contact-form', []);
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
        $I->submitForm('#contact-form', [
            'ContactForm[name]' => 'tester',
            'ContactForm[email]' => 'tester.email',
            'ContactForm[subject]' => 'test subject',
            'ContactForm[body]' => 'test content',
            'ContactForm[verifyCode]' => 'testme',
        ]);
        $I->expectTo('see that email address is wrong');
        $I->dontSee('Необходимо заполнить «Name»', '.help-inline');
        $I->see('Значение «Email» не является правильным email адресом');
        $I->dontSee('Необходимо заполнить «Subject»', '.help-inline');
        $I->dontSee('Необходимо заполнить «Body»', '.help-inline');
        $I->dontSee('Неправильный проверочный код', '.help-inline');
    }

    public function submitFormSuccessfully(\FunctionalTester $I)
    {
        $I->submitForm('#contact-form', [
            'ContactForm[name]' => 'tester',
            'ContactForm[email]' => 'tester@example.com',
            'ContactForm[subject]' => 'test subject',
            'ContactForm[body]' => 'test content',
            'ContactForm[verifyCode]' => 'testme',
        ]);
        $I->seeEmailIsSent();
        $I->dontSeeElement('#contact-form');
        $I->see('Thank you for contacting us. We will respond to you as soon as possible.');        
    }
}
