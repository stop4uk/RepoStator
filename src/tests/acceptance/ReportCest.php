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
            'report' => ReportFixture::class
        ]);

        $I->amLogin(['email' => 'admin@test.loc', 'password' => '12345']);
        $I->amOnPage('/reports');
        $I->waitForText('Список отчетов', 15, 'h3');
    }

    public function list(AcceptanceTester $I): void
    {
        $I->see('Тестовый_отчет');
        $I->see('Тестовый_отчет2');
        $I->see('Тестовый_отчет3');
        $I->seeElement('.bi-question-circle');
        $I->seeElement('.bi-circle-fill.me-2.text-danger');
    }

    public function search(AcceptanceTester $I): void
    {
        $I->click('#searchCardButton');
        $I->fillField('#reportsearch-name', 'Тестовый_отчет2');
        $I->click('Поиск');
        $I->waitForElementVisible('tr[data-key="1"]', 15);
    }

    public function create(AcceptanceTester $I): void
    {
        $I->click('Новый отчет');
        $I->waitForText('Новый отчет', 15 , 'h3');
        $I->fillField('#reportmodel-name', 'Тестовый отчет5');
        $I->fillField('#reportmodel-left_period', '2880');
        $I->fillField('#reportmodel-block_minutes', '15');
        $I->click('Добавить');

        $I->waitForText('Перерыв передачи: 2 дня', 15);
        $I->waitForText('Закрывается за 30 минут', 15);
    }

    public function view(AcceptanceTester $I): void
    {
        $I->click('#viewButton_1');
        $I->waitForText('Просмотр отчета', 15, 'h3');
    }

    public function edit(AcceptanceTester $I): void
    {
        $I->click('#editButton_1');
        $I->waitForText('Редактирование отчета', 15, 'h3');
        $I->fillField('#reportmodel-left_period', '2880');
        $I->fillField('#reportmodel-block_minutes', '15');
        $I->click('Обновить');

        $I->amOnPage('/reports');
        $I->waitForText('Перерыв передачи: 2 дня', 15);
        $I->waitForText('Закрывается за 30 минут', 15);
    }

    public function delete(AcceptanceTester $I): void
    {
        $I->amOnPage('/reports/edit?id=1');
        $I->waitForText('Редактирование отчета', 15, 'h3');
        $I->click('Удалить');
        $I->acceptPopup();
        $I->waitForText('Данная запись НЕАКТИВНА', 15);
    }

    public function enable(AcceptanceTester $I): void
    {
        $I->amOnPage('/reports/view?id=3');
        $I->waitForElementClickable('#enableButton_3');
        $I->click('Сделать карточку активной');
        $I->waitForText('Редактирование отчета', 15, 'h3');
    }
}