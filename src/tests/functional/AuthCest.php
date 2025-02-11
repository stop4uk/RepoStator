<?php

namespace root\tests\functional;

use root\tests\{
    FunctionalTester,
    fixtures\UserFixture
};

final class AuthCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->haveFixtures([
            'user' => UserFixture::class
        ]);

        $I->amOnRoute('/login');
    }

    public function checkEmpty(FunctionalTester $I): void
    {
        $I->submitForm('#login-form', $this->formParams('', ''));
        $I->see('Необходимо заполнить «Email»');
        $I->see('Необходимо заполнить «Пароль»');
    }

    public function checkWrongPassword(FunctionalTester $I): void
    {
        $I->submitForm('#login-form', $this->formParams('admin@test.loc', 'qwerty'));
        $I->see('Вы указали неверный пароль');
    }

    public function loginByAdmin(FunctionalTester $I): void
    {
        $I->submitForm('#login-form', $this->formParams('admin@test.loc', '12345'));
        $I->see('Администрирование');
    }

    public function loginByUserWithDataRole(FunctionalTester $I): void
    {
        $I->submitForm('#login-form', $this->formParams('user1@test.loc', '12345'));
        $I->see('Передать отчет');
        $I->dontSee('Контроль передачи');
        $I->dontSee('Администрирование');
    }

    private function formParams($email, $password): array
    {
        return [
            'LoginForm[email]' => $email,
            'LoginForm[password]' => $password,
        ];
    }
}