<?php

class IterateOverCest
{
    public function getListAllElementsData(NoGuy $I)
    {
        $editors = $I->getValue('users.editors');
        $iterationCounter = 0;

        $I->iterateOver('users.editors', function ($item, $index)
            use ($I, $editors, & $iterationCounter)
        {
            $I->assertEquals($iterationCounter, $index);
            $I->assertEquals($editors[$index], $item);
            $iterationCounter += 1;
        });

        $I->assertEquals(count($editors), $iterationCounter);
    }

    public function iterateThroughOneElementList(NoGuy $I)
    {
        $iterationCounter = 0;

        $I->iterateOver('users.admins', function ($item, $index)
            use ($I, & $iterationCounter)
        {
            $I->assertEquals(0, $index);
            $I->assertEquals('dev-admin', $item['username']);
            $iterationCounter += 1;
        });

        $I->assertEquals(1, $iterationCounter);
    }

    public function passAssocArrayKeyNamesAsSecondParam(NoGuy $I)
    {
        $listKeys = array_keys($I->getValue('headers'));
        $iterationCounter = 0;

        $I->iterateOver('headers', function ($item, $keyName)
            use ($I, $listKeys, & $iterationCounter)
        {
            $I->assertTrue(in_array($keyName, $listKeys));
            $I->assertNotNull($item);
            $iterationCounter += 1;
        });

        $I->assertEquals(count($listKeys), $iterationCounter);
    }
}
