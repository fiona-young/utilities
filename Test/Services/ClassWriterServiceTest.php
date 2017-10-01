<?php
namespace Matters\Utilities\Test\Dtos;

use Matters\Utilities\Dtos\DtoGeneratorInfo;
use Matters\Utilities\Dtos\DtoTemplate;
use Matters\Utilities\Services\DtoClassWriterService;
use Matters\Utilities\Test\TestCase;

class ClassWriterServiceTest extends TestCase
{
    /** @var  DtoClassWriterService */
    private $subject;

    public function setUp()
    {
        $this->subject = new DtoClassWriterService();
    }

    public function testOne()
    {
        $flattenArrayList = [
            'DbEngine' => ['db', 'engine'],
            'DbUsername' => ['db', 'username'],
            "Keys" => ["keys"],
            "Level1Level2Level3"=>['level1','level2','level3']
        ];
        $dtoGeneratorInfo = new DtoTemplate(["className"=>"SettingsDto","namespace"=>"Matters\Utilities\Test\Dtos"]);
        $text = $this->subject->getClassText($dtoGeneratorInfo, $flattenArrayList);
        file_put_contents('SettingsDto.php', $text);
    }
}
