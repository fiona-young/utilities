<?php
namespace Matters\Utilities\Test\Containers;
use Exception;
use Matters\Utilities\Containers\Symfony;
use Matters\Utilities\Test\TestCase;


class ContainerTest extends TestCase {

    /** @var \UtilitiesCachedDIContainer $subject */
    private $subject;
    const ERROR_MESSAGE = 'Container id: %s '."\n\t".'error(%s) %s '."\n\t".'in %s on line %s'."\n";

    public function setUp() {
        $container = Symfony::getInstance();
        $container->compile();
        $this->subject = $container->getContainer();
    }

    public function testDefinedServices() {
        foreach ($this->subject->getServiceIds() as $sId) {
            $this->assertNotNull($this->subject->get($sId));
        }
    }

    public function testInvalidService() {
        $this->setExpectedException( 'InvalidArgumentException', 'You have requested a non-existent service "bob".' );

        $this->subject->get( 'Bob' );
    }

    public function testDefinedServicesWithErrors() {
        $oldErrorHandler = set_error_handler('Matters\Utilities\Test\Containers\ContainerTestErrorHandler');
        $failure = [];
        foreach ($this->subject->getServiceIds() as $sId) {
            try {
                $this->assertNotNull($this->subject->get($sId));
            } catch (ContainerTestException $e) {
                $failure = $this->handleErrors($failure, $sId, $e);
            }
        }
        if(count($failure)){
            $this->fail(implode("\n",$failure));
        }
        set_error_handler($oldErrorHandler);
    }

    private function handleErrors(array $failure, $sId, ContainerTestException $e){
        $string = sprintf(self::ERROR_MESSAGE, $sId, $e->getCode(), $e->getMessage(), $e->getFile(),
            $e->getLine());
        $failure[$sId] = $string;
        return $failure;
    }


}

function ContainerTestErrorHandler($errorType, $message, $file, $line, $context)
{
    // check if @ error supression is in place
    $error_reporting = error_reporting();
    if ($error_reporting == 0) {
        return true;
    }
    $continueFrom = [E_WARNING =>'WARNING', E_STRICT => 'STRICT'];
    if (array_key_exists($errorType,$continueFrom)) {
        return true;
    }
    throw new ContainerTestException($errorType, $message, $file, $line, $context);
}

class ContainerTestException extends Exception{
    protected $context;
    public function __construct($code,$message = "", $file, $line, $context)
    {
        parent::__construct($message, $code);
        $this->file = $file;
        $this->line = $line;
        $this->context = $context;
    }
}