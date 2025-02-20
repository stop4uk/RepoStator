<?php

namespace acceptance;

use root\tests\{
    AcceptanceTester,
    fixtures\ReportFormTemplateFixture
};

final class TemplateCest
{
    public function _before(AcceptanceTester $I): void
    {
        $I->haveFixtures([
            'user' => ReportFormTemplateFixture::class
        ]);

        $I->amLogin(['email' => 'admin@test.loc', 'password' => '12345']);
        $I->amOnPage('/reports/template');
        $I->waitForText('Список шаблонов', 15, 'h3');
    }

    public function list(AcceptanceTester $I): void
    {

    }

    public function search(AcceptanceTester $I): void
    {

    }

    public function create(AcceptanceTester $I): void
    {

    }

    public function view(AcceptanceTester $I): void
    {

    }

    public function edit(AcceptanceTester $I): void
    {

    }

    public function delete(AcceptanceTester $I): void
    {

    }

    public function enable(AcceptanceTester $I): void
    {

    }
}