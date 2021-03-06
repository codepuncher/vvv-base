<?php
/**
 *
 */

namespace JPry\VVVBase;

use Monolog\Handler\NullHandler;
use Monolog\Logger;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;

class ProvisionerTest extends TestCase
{
    /**
     * @var Provisioner
     */
    protected $provisioner;

    /** @var \mysqli */
    protected $db_mock;

    /** @var  vfsStreamDirectory */
    protected $root;


    public function setUp()
    {
        // Wrap filesystem stream for cleaner testing.
        $this->root = vfsStream::setup();

        if (null === $this->db_mock) {
            $this->db_mock = $this->getMockBuilder('\mysqli')
                                  ->disableOriginalConstructor()
                                  ->disableOriginalClone()
                                  ->getMock();
            $this->db_mock->expects($this->atMost(3))
                          ->method('query');
        }

        $this->provisioner = new Provisioner(
            new ProcessBuilder(),
            $this->db_mock,
            $this->root->url(),
            'provision-test',
            array(),
            new Logger('provisionTest', array(new NullHandler()))
        );
    }


    protected function getPublicMethod($method)
    {
        $reflection = new \ReflectionClass($this->provisioner);
        $method     = $reflection->getMethod($method);
        $method->setAccessible(true);

        return $method;
    }


    protected function getPublicProperty($property)
    {
        $reflection = new \ReflectionClass($this->provisioner);
        $property   = $reflection->getProperty($property);
        $property->setAccessible(true);

        return $property;
    }


    public function testSiteSetup()
    {
        $site  = $this->getPublicProperty('site');
        $value = $site->getValue($this->provisioner);

        $this->assertArrayNotHasKey('admin_user', $value);
        $this->assertEquals('admin', $value['admin_user']);
    }


    public function testCreateLogs()
    {
        $this->assertFalse($this->root->hasChild('log'));
        $vm_dir = $this->getPublicProperty('vm_dir');
        $vm_dir->setValue($this->provisioner, $this->root->url());

        $createLogs = $this->getPublicMethod('createLogs');
        $createLogs->invoke($this->provisioner);
        $this->assertTrue($this->root->hasChild('log'));
        $this->assertTrue($this->root->hasChild('log/error.log'));
        $this->assertTrue($this->root->hasChild('log/access.log'));
    }


    public function testCreateBaseDir()
    {
        $this->assertFalse($this->root->hasChild('htdocs'));
        $base_dir = $this->getPublicProperty('base_dir');
        $base_dir->setValue($this->provisioner, $this->root->url() . '/htdocs');

        $createBaseDir = $this->getPublicMethod('createBaseDir');
        $createBaseDir->invoke($this->provisioner);
        $this->assertTrue($this->root->hasChild('htdocs'));
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Invalid installer type: provisiontest
     */
    public function testInstallHelperException()
    {
        $helper = $this->getPublicMethod('installHelper');
        $helper->invoke($this->provisioner, 'provisiontest', array());
    }


    public function testGetCmd()
    {
        $getCmd = $this->getPublicMethod('getCmd');

        // Ensure proper handling of different values
        /** @var Process $result */
        $result = $getCmd->invoke(
            $this->provisioner,
            array('foo'),
            array(
                'bar' => false,
                'baz' => 'some value',
                'boo' => null,
            )
        );
        $this->assertEquals("'foo' '--baz=some value' '--boo'", $result->getCommandLine());
    }
}
