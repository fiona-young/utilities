<?php

namespace Matters\Utilities\Test\Services;

use Matters\Utilities\Services\FileService;
use Matters\Utilities\Services\HelperFunctionService;
use Matters\Utilities\Test\TestCase;

class FileServiceTest extends TestCase
{
    /** @var  HelperFunctionService | \PHPUnit_Framework_MockObject_MockObject */
    private $helpers;
    /** @var  FileService */
    private $subject;

    public function setUp()
    {
        $this->helpers = $this->getActualMock(HelperFunctionService::class);
        $this->subject = new FileService($this->helpers);
    }

    public function testGetDirectory()
    {
        $fileLocation = 'input file directory';
        $directory = 'returned directory';
        $this->helpers->expects($this->once())->method('dirName')->with($fileLocation)->willReturn($directory);
        $this->assertEquals($directory.'/', $this->subject->getDirectory($fileLocation));
    }

    /**
     * @expectedException \Matters\Utilities\Exceptions\FileServiceException
     * @expectedExceptionMessage file (input file location) could not be written
     */
    public function testWriteFile_failure()
    {
        $fileLocation = 'input file location';
        $data = 'some data';
        $this->helpers->expects($this->once())->method('filePutContents')->with($fileLocation,
            $data)->willReturn(false);
        $this->subject->writeFile($fileLocation, $data);
    }


    public function getSuccessProvider()
    {
        return [
            'null' => [null],
            'true' => [true]
        ];
    }

    /**
     * @param $returnValue
     * @dataProvider getSuccessProvider
     */
    public function testWriteFile_success($returnValue)
    {
        $fileLocation = 'input file location';
        $data = 'some data';
        $this->helpers->expects($this->once())->method('filePutContents')->with($fileLocation,
            $data)->willReturn($returnValue);
        $this->subject->writeFile($fileLocation, $data);
    }

    public function testGetFromRunningDirectory()
    {
        $runningDirectory = 'running from the directory';
        $fileLocation = '../../fileLocation.json';
        $this->helpers->expects($this->once())->method('getCwd')->willReturn($runningDirectory);
        $this->assertEquals($runningDirectory.'/'.$fileLocation,
            $this->subject->getFromRunningDirectory($fileLocation));
    }

    /**
     * @expectedException \Matters\Utilities\Exceptions\FileServiceException
     * @expectedExceptionMessage location (../../fileLocation.json) unreadable
     */
    public function testGetDecodedJsonFromFile_whenFileMissing()
    {
        $fileLocation = '../../fileLocation.json';
        $this->helpers->expects($this->once())->method('fileGetContents')->with($fileLocation)->willReturn(false);
        $this->assertEquals($fileLocation, $this->subject->getDecodedJsonFromFile($fileLocation, false));
    }

    /**
     * @expectedException \Matters\Utilities\Exceptions\FileServiceException
     * @expectedExceptionMessage string from (../../fileLocation.json) is unparsible json (not json)
     */
    public function testGetDecodedJsonFromFile_whenJsonUnparsable()
    {
        $fileLocation = '../../fileLocation.json';
        $jsonString = 'not json';
        $this->helpers->expects($this->once())->method('fileGetContents')->with($fileLocation)->willReturn($jsonString);
        $this->assertEquals($fileLocation, $this->subject->getDecodedJsonFromFile($fileLocation, false));
    }

    public function testGetDecodedJsonFromFile_decodesAsStdObj()
    {
        $fileLocation = '../../fileLocation.json';
        $jsonString = json_encode(['red' => 'blue']);
        $this->helpers->expects($this->once())->method('fileGetContents')->with($fileLocation)->willReturn($jsonString);
        $stdObj = $this->subject->getDecodedJsonFromFile($fileLocation, false);
        $this->assertInstanceOf(\stdClass::class, $stdObj);
        $this->assertEquals('blue', $stdObj->red);
    }

    public function testGetDecodedJsonFromFile_decodesAsArray()
    {
        $fileLocation = '../../fileLocation.json';
        $returnedArray = ["type" => "one", "db" => ["host" => "localhost", "database" => "genetics"]];
        $jsonString = json_encode($returnedArray);
        $this->helpers->expects($this->once())->method('fileGetContents')->with($fileLocation)->willReturn($jsonString);
        $this->assertEquals($returnedArray, $this->subject->getDecodedJsonFromFile($fileLocation));
    }
}