<?php

namespace acceptance;

use root\tests\{
    AcceptanceTester,
    fixtures\ReportConstantFixture
};

final class ConstantCest
{
    public function _before(AcceptanceTester $I): void
    {
        $I->haveFixtures([
            'user' => ReportConstantFixture::class
        ]);

        $I->amLogin(['email' => 'admin@test.loc', 'password' => '12345']);
        $I->amOnPage('/reports/constant');
        $I->waitForText('Список констант', 15, 'h3');
    }

    public function list(AcceptanceTester $I): void
    {
        $I->see('ТестКонстанта1');
        $I->see('ТестКонстанта2');
        $I->see('ТестКонстанта3');
        $I->seeElement('.bi-circle-fill.me-2.text-danger');
    }

    public function search(AcceptanceTester $I): void
    {
        $I->click('#searchCardButton');
        $I->fillField('#constantsearch-name', 'Тестовый_отчет2');
        $I->click('Поиск');
        $I->waitForElementVisible('tr[data-key="1"]', 15);
    }

    public function create(AcceptanceTester $I): void
    {
        $I->click('Новая константа');
        $I->waitForText('Новая константа', 15 , 'h3');
        $I->fillField('#constantmodel-record', 'record_testNew');
        $I->fillField('#constantmodel-name', 'Добавленная константа');
        $I->fillField('#constantmodel-name_full', 'Полное название добавленной константы');
        $I->executeJS('$("#constantmodel-description").summernote("insertText", "Только что добавленная константа")');
        $I->click('Добавить');

        $I->waitForText('Добавленная константа', 15);
        $I->waitForText('#record_testNew', 15);
        $I->seeElement('.bi-question-circle');
    }

    public function massCreate(AcceptanceTester $I): void
    {
        $I->click('Массовое добавление');
        $I->waitForText('Массовое добавление', 15 , 'h3');

        $I->fillField('#constantmodel-0-record', 'rrr_test1');
        $I->fillField('#constantmodel-0-name', 'ттт_конст');
        $I->fillField('#constantmodel-0-name_full', 'ттт_конст_полное');
        $I->click('.bi.bi-plus');
        $I->waitForElementVisible('#constantmodel-1-record');
        $I->fillField('#constantmodel-1-record', 'rrr_test2');
        $I->fillField('#constantmodel-1-name', 'ттт_конст2');
        $I->fillField('#constantmodel-1-name_full', 'ттт_конст_полное2');
        $I->click('#copyButton_table-constant_0');
        $I->waitForElementVisible('#constantmodel-2-record');
        $I->assertEquals('rrr_test1', $I->grabValueFrom('#constantmodel-2-record'));
        $I->fillField('#constantmodel-2-record', 'rrr_test3');
        $I->fillField('#constantmodel-2-name', 'ттт_конст3');
        $I->fillField('#constantmodel-2-name_full', 'ттт_конст_полное3');

        $I->click('Добавить константы');
        $I->waitForText('rrr_test1');
        $I->waitForText('rrr_test2');
        $I->waitForText('rrr_test3');
    }

    public function view(AcceptanceTester $I): void
    {
        $I->click('#viewButton_1');
        $I->waitForText('Просмотр константы', 15, 'h3');
    }

    public function edit(AcceptanceTester $I): void
    {
        $I->click('#editButton_1');
        $I->waitForText('Редактирование константы', 15, 'h3');
        $I->fillField('#constantmodel-record', 'rrr3');
        $I->fillField('#constantmodel-name', 'ттт_конст3');
        $I->fillField('#constantmodel-name_full', 'ттт_конст3_полное');
        $I->click('Обновить');
        $I->amOnPage('/reports/constant');
        $I->see('rrr3');
    }

    public function delete(AcceptanceTester $I): void
    {
        $I->amOnPage('/reports/constant/edit?id=1');
        $I->waitForText('Редактирование константы', 15, 'h3');
        $I->click('Удалить');
        $I->acceptPopup();
        $I->waitForText('Данная запись НЕАКТИВНА', 15);
    }

    public function enable(AcceptanceTester $I): void
    {
        $I->amOnPage('/reports/constant/view?id=7');
        $I->waitForElementClickable('#enableButton_7');
        $I->click('Сделать карточку активной');
        $I->waitForText('Редактирование константы', 15, 'h3');
    }
}