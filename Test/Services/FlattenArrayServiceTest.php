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
        $flattenedArrayList = $this->subject->getFlattenedArrayList($data);
        $this->assertCount(2,$flattenedArrayList);
        #$this->assertEquals(['keys'=>["db","host"],'method'=>'DbHost'],$flattenedArrayList[0]->getAttributes());
       # $this->assertEquals(['keys'=>["db","database"],'method'=>'DbDatabase'],$flattenedArrayList[1]->getAttributes());
    }
}