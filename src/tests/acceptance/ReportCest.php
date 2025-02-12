<?php

namespace root\tests\acceptance;

use root\tests\{
    AcceptanceTester,
    fixtures\ReportFixture
};

final class ReportCest
{
    public function _before(AcceptanceTester $I): void
    {
        $I->haveFixtures([
            'user' => ReportFixture::class
        ]);
    }

    public function showListByAdmin(AcceptanceTester $I): void
    {
        $I->amLogin(['email' => 'admin@test.loc', 'password' => '12345']);
        $I->amOnPage('/reports');

        $I->see('Список отчетов');
        $I->see('Тестовый_отчет');
        $I->see('Тестовый_отчет2');
        $I->see('Тестовый_отчет3');
        $I->seeElement('.bi-question-circle');
        $I->seeElement('.bi-circle-fill.me-2.text-danger');
    }

    public function showListByUser(AcceptanceTester $I): void
    {
        $I->amLogin(['email' => 'user2@test.loc', 'password' => '12345']);
        $I->amOnPage('/reports');

        $I->see('Список отчетов');
        $I->dontSee('Тестовый_отчет');
        $I->dontSee('Тестовый_отчет2');
        $I->dontSee('Тестовый_отчет3');
        $I->dontSeeElement('.bi-circle-fill.me-2.text-danger');
        $I->see('Тестовый отчет4');
        $I->seeElement('.bi-question-circle');
    }
}