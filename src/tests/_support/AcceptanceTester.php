<?php

declare(strict_types=1);

namespace root\tests;

use yii\helpers\FileHelper;

/**
 * Inherited Methods
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

    /**
     * Define custom actions here
     */

    final public function amLogin(array $userData): void
    {
        $this->amOnPage('/login');
        $this->see('Авторизация');
        $this->fillField('#loginform-email', $userData['email']);
        $this->fillField('#loginform-password', $userData['password']);
        $this->click('Вход');
        $this->waitForElement('.bi-person-circle', 20);
    }

    final public function clearEmails(): void
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

    final public function checkQuery(): int
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

        return 0;
    }
}
