<?php
namespace Matters\Utilities\Test\Dtos;

use Matters\Utilities\Dtos\DtoGeneratorInfo;
use Matters\Utilities\Services\ClassWriterService;
use Matters\Utilities\Test\TestCase;

class ClassWriterServiceTest extends TestCase
{
    /** @var  ClassWriterService */
    private $subject;

    public function setUp()
    {
        $this->subject = new ClassWriterService();
    }

    public function testOne()
    {
        $flattenArrayList = [
            'DbEngine' => ['db', 'engine'],
            'DbUsername' => ['db', 'username'],
            "Keys" => ["keys"],
            "Level1Level2Level3"=>['level1','level2','level3']
        ];
        $dtoGeneratorInfo = new DtoGeneratorInfo(["className"=>"SettingsDto","namespace"=>"Matters\Utilities\Test\Dtos"]);
        $text = $this->subject->getDtoClassText($dtoGeneratorInfo, $flattenArrayList);
        file_put_contents('SettingsDto.php', $text);
    }
}
