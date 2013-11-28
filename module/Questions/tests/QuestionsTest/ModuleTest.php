<?php
namespace QuestionsTest;

use Questions\Module;
use Traversable;

class ModuleTest extends \PHPUnit_Framework_TestCase
{
    public function testGetConfig()
    {
        $module = new Module();
        $config = $module->getConfig();
        $this->assertInternalType('array', $config);
    }

    public function testGetAutoloaderConfig()
    {
        $module = new Module();
        $config = $module->getAutoloaderConfig();
        if (!is_array($config) && !($config instanceof Traversable)) {
            $this->fail('getAutoloaderConfig expected to return array or Traversable');
        }
    }

    public function testTravisBuild()
    {
        $this->assertTrue(false, 'atrodo veikia :D');
    }
}
