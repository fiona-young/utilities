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
        $this->assertEquals([],$this->subject->getWalkedList(null));
    }

    public function testWithList()
    {
        $data = ["one"=>"unused","two" => "ignored"];
        $this->assertEquals(['One' => ['one'], 'Two' => ['two']],
            $this->subject->getWalkedList($data));
    }

    public function testWith2D()
    {
        $data = ["db" => ["host" => "localhost", "database" => "genetics"]];
        $this->assertEquals(['DbHost' => ['db', 'host'], 'DbDatabase' => ['db', 'database']],
            $this->subject->getWalkedList($data));
    }

    public function testWith3D()
    {
        $data = ["one" => ["two" => ["three" => true], "twoA" => ["threeA" => true]]];
        $this->assertEquals(['OneTwoThree' => ['one', 'two', 'three'],
            'OneTwoAThreeA' => ['one', 'twoA', 'threeA']],
            $this->subject->getWalkedList($data));
    }

    public function testMixedData()
    {
        $data = ["type"=>"one","db" => ["host" => "localhost", "database" => "genetics"]];
        $this->assertEquals(['DbHost' => ['db', 'host'], 'DbDatabase' => ['db', 'database'],"Type"=>['type']],
            $this->subject->getWalkedList($data));
    }
}