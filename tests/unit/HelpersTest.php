<?php

use Codeception\Test\Unit;

use Phalcon\DiInterface;
use Phalcon\DispatcherInterface;

class HelpersTest extends Unit
{
    /**
     * UnitTester Object
     * @var \UnitTester
     */
    protected $tester;

    protected $appPath;
    protected $cachePath;
    protected $configPath;

    public function _before()
    {
        parent::_before();

        $this->appPath = dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'app';
        $this->cachePath = $this->appPath . DIRECTORY_SEPARATOR . 'cache';
        $this->configPath = $this->appPath . DIRECTORY_SEPARATOR . 'config';
    }

    public function testAppPath()
    {
        $this->assertEquals($this->appPath, app_path());
        $this->assertEquals($this->appPath . DIRECTORY_SEPARATOR . 'foo', app_path('foo'));
        $this->assertEquals($this->appPath . DIRECTORY_SEPARATOR . 'bar/', app_path('bar/'));

        $this->tester->amInPath(app_path());
        $this->tester->seeFileFound('config.php', 'config');
    }

    public function testCachePath()
    {
        $this->assertEquals($this->cachePath, cache_path());
        $this->assertEquals($this->cachePath . DIRECTORY_SEPARATOR . 'foo', cache_path('foo'));
        $this->assertEquals($this->cachePath . DIRECTORY_SEPARATOR . 'bar/', cache_path('bar/'));

        $this->tester->amInPath(app_path());
        $this->tester->seeFileFound('cache');
    }

    public function testConfigPath()
    {
        $this->assertEquals($this->configPath, config_path());
        $this->assertEquals($this->configPath . DIRECTORY_SEPARATOR . 'foo', config_path('foo'));
        $this->assertEquals($this->configPath . DIRECTORY_SEPARATOR . 'bar/', config_path('bar/'));

        $this->tester->amInPath(app_path());
        $this->tester->seeFileFound('config');
    }

    public function testContainerFacade()
    {
        $this->assertInstanceOf(DiInterface::class, container());
        $this->assertInstanceOf(DispatcherInterface::class, container('dispatcher'));
    }

    public function testValueFacade()
    {
        $this->assertNull(value(null));
        $this->assertFalse(value(false));
        $this->assertEquals('', value(''));
        $this->assertEquals('foo', value(function () { return 'foo'; }));
    }

    public function testEnvFacade()
    {
        $this->assertNull(env('non-existent-key-here'));
        $this->assertTrue(env('non-existent-key-here', true));
        $this->assertEquals($_ENV['APP_URL'], env('APP_URL'));
    }

    public function testEnvironmentFacade()
    {
        $this->assertFalse(environment('non-existent-environment-here'));
        $this->assertFalse(environment(['non-existent-environment-here', 'non-existent-environment-here']));
        $this->assertFalse(environment([]));
        $this->assertFalse(environment(false));
        $this->assertFalse(environment(null));
        $this->assertFalse(environment('production'));
        $this->assertFalse(environment('staging'));
        $this->assertFalse(environment(['staging', 'production']));

        $this->assertTrue(environment(['staging', 'production', 'development', 'testing']));
        $this->assertTrue(environment([APPLICATION_ENV]));
        $this->assertTrue(environment() === APPLICATION_ENV);
    }

    /**
     * @dataProvider providerHashEquals
     * @param mixed $knownString
     * @param mixed $userString
     * @param bool $expected
     */
    public function testHasEquals($knownString, $userString, $expected)
    {
        if (version_compare(PHP_VERSION, '5.6.0') >= 0) {
            $this->markTestSkipped("The hash_equals already provided by PHP v".PHP_VERSION);
        }

        $this->assertSame($expected, hash_equals($knownString, $userString));
    }

    public function providerHashEquals()
    {
        return [
            ['Phalcon',    'Phalcony',    false],
            ['Phalcony',   'Phalcon',     false],
            ['Phalcon',    'Phalcon',     true],
            ['kristoffer', 'ingemansson', false],
            ['kris',       'ingemansson', false],
            ['Phalcon',    'phalcon',     false],
            [' phalcon',   'phalcon',     false],
            ['phalcon',    'phalcon',     true],
            ['1234567890', '1234567890',  true],
            ['',           'phalcon',     false],
            ['phalcon',    '',            false],
            [null,          123,          false],
            [123,           null,         false],
            [123,           123,          false],
            [123456,        123,          false],
            [null,          'phalcon',    false],
            ['phalcon',     null,         false],
            [[],            false,        false],
            [true,          [],           false],
        ];
    }
}
