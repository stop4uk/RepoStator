<?php

namespace acceptance;

use root\tests\{
    AcceptanceTester,
    fixtures\ReportConstantRuleFixture
};

final class ConstantRuleCest
{
    public function _before(AcceptanceTester $I): void
    {
        $I->haveFixtures([
            'user' => ReportConstantRuleFixture::class
        ]);

        $I->amLogin(['email' => 'admin@test.loc', 'password' => '12345']);
        $I->amOnPage('/reports/constantrule');
        $I->waitForText('Список правил', 15, 'h3');
    }

    public function list(AcceptanceTester $I): void
    {
        $I->see('constantRuleTest');
    }

    public function search(AcceptanceTester $I): void
    {
        $I->click('#searchCardButton');
        $I->fillField('#constantrulesearch-record', 'testRecord');
        $I->click('Поиск');
        $I->waitForText('Правила сложения отсутствуют', 15);
    }

    public function create(AcceptanceTester $I): void
    {
        $I->click('Новое правило');
        $I->waitForText('Новое правило', 15 , 'h3');
        $I->fillField('#constantrulemodel-record', 'testRule');
        $I->fillField('#constantrulemodel-name', 'Тестовое правило');
        $I->fillField('#constantrulemodel-rule', '"record1"+"record2"');
        $I->executeJS('$("#constantrulemodel-description").summernote("insertText", "Только что добавленное правило")');

        $I->executeJS('$("#constantHelper").select2("open")');
        $I->waitForElementVisible('.select2-container--krajee-bs5', 15);
        $I->waitForText('ТестКонстанта3');

        $I->click('Добавить');
        $I->waitForText('#testRule', 15);
        $I->seeElement('.bi-question-circle');
    }

    public function view(AcceptanceTester $I): void
    {
        $I->click('#viewButton_1');
        $I->waitForText('Просмотр правила', 15, 'h3');
    }

    public function edit(AcceptanceTester $I): void
    {
        $I->click('#editButton_1');
        $I->waitForText('Редактирование правила', 15, 'h3');
        $I->fillField('#constantrulemodel-record', 'ttRrTest');
        $I->fillField('#constantrulemodel-name', 'ТТРРОР');
        $I->click('Обновить');

        $I->amOnPage('/reports/constantrule');
        $I->waitForText('ttRrTest', 15);
        $I->waitForText('ТТРРОР', 15);
    }

    public function delete(AcceptanceTester $I): void
    {
        $I->amOnPage('/reports/constantrule/edit?id=1');
        $I->waitForText('Редактирование правила', 15, 'h3');
        $I->click('Удалить');
        $I->acceptPopup();
        $I->waitForText('Данная запись НЕАКТИВНА', 15);
    }

    public function enable(AcceptanceTester $I): void
    {
        $I->amOnPage('/reports/constantrule/view?id=2');
        $I->click('Сделать карточку активной');
        $I->waitForText('Редактирование правила', 15, 'h3');
    }
}