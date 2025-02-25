<?php

namespace acceptance;

use root\tests\{
    AcceptanceTester,
    fixtures\ReportStructureFixture
};

final class StructureCest
{
    public function _before(AcceptanceTester $I): void
    {
        $I->haveFixtures([
            'structure' => ReportStructureFixture::class
        ]);

        $I->amLogin(['email' => 'admin@test.loc', 'password' => '12345']);
        $I->amOnPage('/reports/structure');
        $I->waitForText('Список структур', 15, 'h3');
    }

    public function list(AcceptanceTester $I): void
    {
        $I->waitForText('Структура 1', 15);
        $I->waitForText('Структура 2', 15);
    }

    public function search(AcceptanceTester $I): void
    {
        $I->click('#searchCardButton');
        $I->fillField('#structuresearch-name', '1234');
        $I->click('Поиск');
        $I->waitForText('Стурктуры для просмотра отсутствуют', 15);
    }

    public function create(AcceptanceTester $I): void
    {
        $I->click('Новая структура');
        $I->waitForText('Новая структура', 15, 'h3');

        $I->fillField('#structuremodel-name', 'Структура 50');
        $I->executeJS('$("#structuremodel-report_id").select2("open")');
        $I->waitForElementVisible('.select2-container--krajee-bs5', 15);
        $I->click("//li[contains(text(),'Тестовый_отчет')]");

        $I->executeJS('$("#structuremodel-groups_only").select2("open")');
        $I->waitForElementVisible('.select2-container--krajee-bs5', 15);
        $I->click("//li[contains(text(),'Тестовая группа 2')]");

        $I->executeJS('$("#structuremodel-contentconstants-0").select2("open")');
        $I->waitForElementVisible('.select2-container--krajee-bs5', 15);
        $I->click("//li[contains(text(),'ТестКонстанта4')]");
        $I->executeJS('$("#structuremodel-contentconstants-0").select2("open")');
        $I->waitForElementVisible('.select2-container--krajee-bs5', 15);
        $I->click("//li[contains(text(),'ТестКонстанта6')]");

        $I->click('Добавить');
        $I->seeInCurrentUrl('/reports/structure');
        $I->waitForText('Структура 50', 15);
        $I->waitForText('1 раздел # 2 показателя', 15);
    }

    public function view(AcceptanceTester $I): void
    {
        $I->click('#viewButton_1');
        $I->waitForText('Просмотр структуры', 14, 'h3');
    }

    public function edit(AcceptanceTester $I): void
    {
        $I->click('#editButton_1');
        $I->waitForText('Редактирование структуры', 15, 'h3');

        $I->executeJS('$("#structuremodel-contentconstants-0").select2("open")');
        $I->waitForElementVisible('.select2-container--krajee-bs5', 15);
        $I->click("//li[contains(text(),'ТестКонстанта6')]");
        $I->click('Обновить');
        $I->amOnPage('/reports/structure');
        $I->waitForText('1 раздел # 4 показателя', 15);
    }

    public function delete(AcceptanceTester $I): void
    {
        $I->amOnPage('/reports/structure/edit?id=1');
        $I->waitForText('Редактирование структуры', 15, 'h3');
        $I->click('Удалить');
        $I->acceptPopup();
        $I->waitForText('Данная запись НЕАКТИВНА', 15);
    }

    public function enable(AcceptanceTester $I): void
    {
        $I->amOnPage('/reports/structure/view?id=3');
        $I->waitForElementClickable('#enableButton_3');
        $I->click('Сделать карточку активной');
        $I->waitForText('Редактирование структуры', 15, 'h3');
    }
}