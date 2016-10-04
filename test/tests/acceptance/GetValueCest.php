<?php

class GetValueCest
{
    public function getExistingDataValue(NoGuy $I)
    {
        $returnedValue = $I->getValue('headers.xAppMode.name');

        $I->assertNotNull($returnedValue);
        $I->assertInternalType('string', $returnedValue);
    }

    public function getExistingArrayData(NoGuy $I)
    {
        $adminData = $I->getValue('users.admins');

        $I->assertNotNull($adminData);
        $I->assertInternalType('array', $adminData);
    }

    public function getSpecificArrayElementData(NoGuy $I)
    {
        $editorName = $I->getValue('users.editors.1.username');

        $I->assertNotNull($editorName);
        $I->assertEquals('john', $editorName);
    }

    public function getExistingArrayWithMultipleElementsData(NoGuy $I)
    {
        $editors = $I->getValue('users.editors');

        $I->assertInternalType('array', $editors);
        $I->assertArrayHasKey('username', $editors[0]);
        $I->assertNotNull($editors[0]['username']);
    }

    public function getAllElementsOfAList(NoGuy $I)
    {
        $editors = $I->getValue('users.editors');

        $I->assertEquals(2, count($editors));
    }

    public function getNonExistingDataValue(NoGuy $I)
    {
        $I->assertNull($I->getValue('non.existing.data'));
    }

    public function getDefaultForNonExistingDataValue(NoGuy $I)
    {
        $name = $I->getValue('non.existing.user.name', 'defaultName');

        $I->assertEquals('defaultName', $name);
    }
}
