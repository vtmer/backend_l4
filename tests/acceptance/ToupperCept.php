<?php
$I = new AcceptanceTester($scenario);
$I->wantTo('perform actions and see result');
$I->amOnPage('index.blade.php');
$I->fillField('name', "I'm Yann");
$I->click('submit');
$I->see('Hello, Yann');
