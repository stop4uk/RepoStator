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
            'template' => ReportFormTemplateFixture::class
        ]);

        $I->amLogin(['email' => 'admin@test.loc', 'password' => '12345']);
        $I->amOnPage('/reports/template');
        $I->waitForText('Список шаблонов', 15, 'h3');
    }

    public function list(AcceptanceTester $I): void
    {
        $I->see('Шаблон 1');
        $I->see('Шаблон 2');
        $I->see('Шаблон 3');
        $I->seeElement('.bi-circle-fill.me-2.text-danger');
    }

    public function search(AcceptanceTester $I): void
    {
        $I->click('#searchCardButton');
        $I->fillField('#templatesearch-name', 'Динамический');
        $I->click('Поиск');
        $I->waitForText('Шаблоны отсутствуют', 15);
    }

    public function view(AcceptanceTester $I): void
    {
        $I->click('#viewButton_1');
        $I->waitForText('Просмотр шаблона', 15, 'h3');
    }

    public function delete(AcceptanceTester $I): void
    {
        $I->amOnPage('/reports/template/edit?id=1');
        $I->waitForText('Редактирование шаблона', 15, 'h3');
        $I->click('Удалить');
        $I->acceptPopup();
        $I->waitForText('Данная запись НЕАКТИВНА', 15);
    }

    public function enable(AcceptanceTester $I): void
    {
        $I->amOnPage('/reports/template/view?id=3');
        $I->waitForElementClickable('#enableButton_3');
        $I->click('Сделать карточку активной');
        $I->waitForText('Редактирование шаблона', 15, 'h3');
    }
}