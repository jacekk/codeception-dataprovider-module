<?php

use Codeception\Exception\ModuleConfigException;
use Codeception\Exception\ModuleException;
use Codeception\Lib\ModuleContainer;
use Codeception\Module\DataProvider;
use Codeception\Util\Stub;

class DataProviderTest extends \Codeception\Test\Unit
{
    /** @var \UnitTester */
    protected $guy;

    /** @var DataProvider */
    protected $module;

    /** @var ModuleContainer */
    protected $moduleContainer;

    /** @var array */
    protected $moduleConfig = [
        DataProvider::PARAM_KEY__DATA_PATH_TPL => '{root}/tests/_data/{file}',
        DataProvider::PARAM_KEY__FILES => [
            'common-provider.yml',
            'env-provider.dev.yml',
        ],
    ];

    protected function _before()
    {
        $this->moduleContainer = Stub::make(ModuleContainer::class);
        $this->module = new DataProvider($this->moduleContainer, $this->moduleConfig);
    }

    protected function _after()
    {
    }

    /** @test */
    public function moduleInstanceShouldHaveBeenCreated()
    {
        $this->guy->assertInstanceOf(DataProvider::class, $this->module);
    }

    /** @test */
    public function shouldReturnNullByDefault()
    {
        $this->module->_initialize();

        $this->assertNull($this->module->getValue('non.existing.data'));
    }

    /** @test */
    public function initializeShouldLoadDataFromGivenFiles()
    {
        $this->module->_initialize();
        $valueFromDataFile = $this->module->getValue('headers.accept');

        $this->assertNotNull($valueFromDataFile);
    }

    /** @test */
    public function shouldThrowExceptionForNonExistingDataAlias()
    {
        $this->module->_initialize();
        $this->guy->expectException(ModuleException::class, function () {
            $this->module->iterateOver('non.existing.data', function () {});
        });
    }

    /** @test */
    public function shouldConvertSingleFileInConfigToArray()
    {
        $configWithOneFile = $this->moduleConfig;
        $configWithOneFile[DataProvider::PARAM_KEY__FILES] = reset($this->moduleConfig[DataProvider::PARAM_KEY__FILES]);

        $this->guy->assertInternalType('string', $configWithOneFile[DataProvider::PARAM_KEY__FILES]);

        $this->module = new DataProvider($this->moduleContainer, $configWithOneFile);
        $this->module->_initialize();
        $valueFromSingleFile = $this->module->getValue('headers.accept');

        $this->assertNotNull($valueFromSingleFile);
    }

    /** @test */
    public function shouldThrowExceptionForNoFilesInConfig()
    {
        $configWithOneFile = $this->moduleConfig;
        $configWithOneFile[DataProvider::PARAM_KEY__FILES] = [];

        $this->guy->assertInternalType('array', $configWithOneFile[DataProvider::PARAM_KEY__FILES]);
        $this->guy->assertEquals(0, count($configWithOneFile[DataProvider::PARAM_KEY__FILES]));

        $this->guy->expectException(ModuleConfigException::class, function () use ($configWithOneFile) {
            $this->module = new DataProvider($this->moduleContainer, $configWithOneFile);
        });
    }


    /** @test */
    public function shouldThrowExceptionifFileIsUnreadable()
    {
        $configWithOneFile = $this->moduleConfig;
        $configWithOneFile[DataProvider::PARAM_KEY__DATA_PATH_TPL] = './not/a/valid/directory/{file}';

        $this->guy->expectException(ModuleConfigException::class, function () use ($configWithOneFile) {
            $this->module = new DataProvider($this->moduleContainer, $configWithOneFile);
        });
    }
}
