<?php
namespace Matters\Utilities\Test\Dtos;
use Matters\Utilities\Dtos\DtoGeneratorInfo;
use Matters\Utilities\Test\TestCase;


class DtoGeneratorInfoTest extends TestCase
{
    private $data;
    public function setUp(){
        $this->data = [
            "dtoData" => ["db" => ["host" => "localhost", "database" => "genetics"]],
            "namespace" => 'my\namespace',
            "className" => 'someClassName',
        ];
    }

    public function testWhenNull(){
        $subject = new DtoGeneratorInfo();
        $this->assertNull( $subject->getDtoData());
        $this->assertNull( $subject->getNamespace());
        $this->assertNull($subject->getClassName());
    }

    public function testWithSetters(){
        $subject = new DtoGeneratorInfo();
        $subject->setDtoData($this->data['dtoData']);
        $subject->setNamespace($this->data['namespace']);
        $subject->setClassName($this->data['className']);
        $this->assertEquals($this->data['dtoData'], $subject->getDtoData());
        $this->assertEquals($this->data['namespace'], $subject->getNamespace());
        $this->assertEquals($this->data['className'], $subject->getClassName());
    }

    public function testConstructor(){
        $subject = new DtoGeneratorInfo($this->data);
        $this->assertEquals($this->data['dtoData'], $subject->getDtoData());
        $this->assertEquals($this->data['namespace'], $subject->getNamespace());
        $this->assertEquals($this->data['className'], $subject->getClassName());
    }
}