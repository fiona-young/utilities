<?php
namespace Matters\Utilities\Test\Dtos;

use Matters\Utilities\Services\FlattenArrayService;
use Matters\Utilities\Test\TestCase;

class FlattenArrayServiceTest extends TestCase
{
    /** @var  FlattenArrayService */
    private $subject;
    public function setUp(){
        $this->subject = new FlattenArrayService();
    }

    public function testWhenNull(){
        $this->assertEquals([],$this->subject->getFlattenedArrayList(null));
    }

    public function testWhenData(){
        $data =  ["db" => ["host" => "localhost", "database" => "genetics"]];
        $this->assertEquals(['DbHost'=>['db','host'],'DbDatabase'=>['db','database']], $this->subject->getFlattenedArrayList($data));
    }
}