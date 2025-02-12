<?php

namespace root\tests\acceptance;

use Yii;
use yii\helpers\FileHelper;

use root\tests\{
    AcceptanceTester,
    fixtures\UserFixture
};
use app\modules\users\entities\UserEntity;

final class AuthCest
{
    public function _before(AcceptanceTester $I): void
    {
        $I->haveFixtures([
            'user' => UserFixture::class
        ]);

        $this->clearEmails();
        $I->amOnPage('/login');
    }

    public function checkEmpty(AcceptanceTester $I): void
    {
        $I->submitForm('#login-form', $this->formParams('', ''));
        $I->waitForText('Необходимо заполнить «Email»', 15);
        $I->waitForText('Необходимо заполнить «Пароль»', 15);
    }

    public function checkWrongPassword(AcceptanceTester $I): void
    {
        $I->submitForm('#login-form', $this->formParams('admin@test.loc', 'qwerty'));
        $I->waitForText('Вы указали неверный пароль', 15);
    }

    public function loginByAdmin(AcceptanceTester $I): void
    {
        $I->submitForm('#login-form', $this->formParams('admin@test.loc', '12345'));
        $I->waitForText('Администрирование', 15);

        $sentEmails = $this->checkQuery();
        $I->assertEquals($sentEmails, 1);
    }

    public function loginByUserWithDataRole(AcceptanceTester $I): void
    {
        $I->submitForm('#login-form', $this->formParams('user1@test.loc', '12345'));
        $I->waitForText('Передать отчет', 15);
        $I->dontSee('Контроль передачи');
        $I->dontSee('Администрирование');

        $sentEmails = $this->checkQuery();
        $I->assertEquals($sentEmails, 1);
    }

    public function recoveryPassword(AcceptanceTester $I): void
    {
        $I->waitForText('Восстановить пароль', 15);
        $I->click('Восстановить пароль');
        $I->waitForText('Восстановление',15);
        $I->fillField('#recoveryform-email', 'user1@test.loc');
        $I->click('Получить инструкцию');

        $sentEmails = $this->checkQuery();
        $I->assertEquals($sentEmails, 1);

        $grabRecoveryKey = $I->grabRecord(UserEntity::class, ['id' => 2]);
        $I->amOnPage('/recovery/process?key=' . $grabRecoveryKey->account_key);
        $I->waitForText('Подтверждение пароля', 15);
        $I->fillField('#recoveryform-password', '12345');
        $I->fillField('#recoveryform-verifypassword', '12345');
        $I->click('Обновить пароль');
        $I->waitForText('Авторизация',15);

        $grabRecoveryKeyAfterUpdatePass = $I->grabRecord(UserEntity::class, ['id' => 2]);
        $I->assertNotEquals($grabRecoveryKey->account_key, $grabRecoveryKeyAfterUpdatePass->account_key);
    }

    public function registerWithVerification(AcceptanceTester $I): void
    {
        $I->waitForText('Зарегистрироваться', 15);
        $I->click('Зарегистрироваться');
        $I->waitForText('Регистрация', 15);
        $I->fillField('#registerform-lastname', 'Фамилия');
        $I->fillField('#registerform-firstname', 'Имя');
        $I->fillField('#registerform-middlename', 'Отчество');
        $I->fillField('#registerform-phone', '9999999999');
        $I->fillField('#registerform-email', 'test_register@test.test');
        $I->fillField('#registerform-password', '12345');
        $I->click('Зарегистрироваться');
        $I->waitForText('Авторизация', 15);

        $sentEmails = $this->checkQuery();
        $I->assertEquals($sentEmails, 1);

        $grabVerifyCode = $I->grabRecord(UserEntity::class, ['email' => 'test_register@test.test']);
        $I->amOnPage('/verification/process?key=' . $grabVerifyCode->account_key);
        $I->waitForText('Авторизация', 15);

        $grabVerifyCodeAfterVerify = $I->grabRecord(UserEntity::class, ['email' => 'test_register@test.test']);
        $I->assertNotEquals($grabVerifyCode->account_key, $grabVerifyCodeAfterVerify->account_key);
    }

    public function registerWithoutVerification(AcceptanceTester $I): void
    {
        Yii::$app->settings->set('auth', 'login_withoutVerification', 1);

        $I->waitForText('Зарегистрироваться', 15);
        $I->click('Зарегистрироваться');
        $I->waitForText('Регистрация', 15);
        $I->fillField('#registerform-lastname', 'Фамилия');
        $I->fillField('#registerform-firstname', 'Имя');
        $I->fillField('#registerform-middlename', 'Отчество');
        $I->fillField('#registerform-phone', '9999999999');
        $I->fillField('#registerform-email', 'test_register@test.test');
        $I->fillField('#registerform-password', '12345');
        $I->click('Зарегистрироваться');
        $I->waitForText('Важных напоминаний и уведомлений нет', 15);

        $sentEmails = $this->checkQuery();
        $I->assertEquals($sentEmails, 1);

        Yii::$app->settings->set('auth', 'login_withoutVerification', 0);
    }

    private function formParams($email, $password): array
    {
        return [
            'LoginForm[email]' => $email,
            'LoginForm[password]' => $password,
        ];
    }

    private function clearEmails(): void
    {
        /**
         * Чистим почтовые уведомления
         */
        $sentEmails = FileHelper::findFiles(Yii::getAlias('@runtime/mail'));
        if ($sentEmails) {
            foreach ($sentEmails as $email) {
                FileHelper::unlink($email);
            }
        }
    }

    private function checkQuery(): int|null
    {
        /**
         * Тут по сути проверяется и работа очереди с супервизором
         * Каждое письмо отправляется через очередь и, если, задач в БД нет, значит и задача должна исполниться
         * Следует учесть, что в данном случае проверяется очередь в стандартной реализации. То есть, через БД
         */
        while(true) {
            $tasks = Yii::$app->db->createCommand('SELECT * FROM {{%queue}}')->execute();
            if (!$tasks) {
                $sentEmails = FileHelper::findFiles(Yii::getAlias('@runtime/mail'));
                if ($sentEmails) {
                    foreach ($sentEmails as $email) {
                        FileHelper::unlink($email);
                    }

                    return count($sentEmails);
                }

                break;
            }

            sleep(2);
        }

        return null;
    }
}