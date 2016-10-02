<?php

class DataProviderCest
{
    public function getExistingDataValue(NoGuy $I)
    {
        $adminName = $I->getValue('users.admins.username');

        $I->assertNotNull($adminName);
        $I->assertInternalType('string', $adminName);
    }

    public function getExistingArrayData(NoGuy $I)
    {
        $admin = $I->getValue('users.admins');

        $I->assertNotNull($admin);
        $I->assertInternalType('array', $admin);
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
