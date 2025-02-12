<?php

declare(strict_types=1);

namespace root\tests;

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
}
