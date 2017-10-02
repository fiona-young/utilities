<?php
namespace Matters\Utilities\Test\Dtos;

use Matters\Utilities\Dtos\DtoTemplate;
use Matters\Utilities\Services\DtoClassWriterService;
use Matters\Utilities\Test\TestCase;

class DtoClassWriterServiceTest extends TestCase
{
    /** @var  DtoTemplate | \PHPUnit_Framework_MockObject_MockObject */
    private $dtoTemplate;
    /** @var  DtoClassWriterService */
    private $subject;

    public function setUp()
    {
        $this->dtoTemplate = $this->getActualMock(DtoTemplate::class);
        $this->subject = new DtoClassWriterService();
    }

    public function testGettersAndSettersOff()
    {
        $flattenArrayList = ['One' => ['one']];
        $this->dtoTemplate->expects($this->atLeastOnce())->method('getNamespace')
            ->willReturn('ns');
        $this->dtoTemplate->expects($this->atLeastOnce())->method('getClassName')
            ->willReturn('Cls');
        $this->dtoTemplate->expects($this->once())->method('getGetters')->with($this->isTrue())->willReturn(false);
        $this->dtoTemplate->expects($this->once())->method('getSetters')->with($this->isTrue())->willReturn(false);
        $text = $this->subject->getClassText($this->dtoTemplate, $flattenArrayList);
        $pattern = $this->createRegExp($this->getTextGettersAndSettersOff(),'^','$');
        $this->assertRegExp($pattern, $text);
        $this->assertNotRegExp($this->createRegExp($this->get1DAttributeGetter()), $text);
    }

    public function test1DArrayGettersOn()
    {
        $flattenArrayList = ['One' => ['one']];
        $this->dtoTemplate->expects($this->once())->method('getGetters')->with($this->isTrue())->willReturn(true);
        $this->dtoTemplate->expects($this->once())->method('getSetters')->with($this->isTrue())->willReturn(false);
        $text = $this->subject->getClassText($this->dtoTemplate, $flattenArrayList);
        $getOneFunction = [
            '    public function getOne($default = null)',
            '    {',
            '       return $this->getAttribute(\'one\', $default);',
            '    }'
        ];
        $this->assertRegExp($this->createRegExp($getOneFunction), $text);
        $this->assertRegExp($this->createRegExp($this->get1DAttributeGetter()), $text);
    }

    public function test1DArraySettersOn()
    {
        $flattenArrayList = ['One' => ['one']];
        $this->dtoTemplate->expects($this->once())->method('getGetters')->with($this->isTrue())->willReturn(false);
        $this->dtoTemplate->expects($this->once())->method('getSetters')->with($this->isTrue())->willReturn(true);
        $text = $this->subject->getClassText($this->dtoTemplate, $flattenArrayList);
        $setOneFunction = [
            '    public function setOne($one)',
            '    {',
            '        $this->data[\'one\'] = $one;',
            '    }'
        ];
        $this->assertRegExp($this->createRegExp($setOneFunction), $text);
        $this->assertNotRegExp($this->createRegExp($this->get1DAttributeGetter()), $text);
    }

    public function test2DArraySettersAndGettersOn()
    {
        $flattenArrayList = [
            'DbEngine' => ['db', 'engine'],
            'DbUsername' => ['db', 'username'],
        ];
        $this->dtoTemplate->expects($this->once())->method('getGetters')->with($this->isTrue())->willReturn(true);
        $this->dtoTemplate->expects($this->once())->method('getSetters')->with($this->isTrue())->willReturn(true);
        $text = $this->subject->getClassText($this->dtoTemplate, $flattenArrayList);
        $getFunction = [
            '    public function getDbEngine($default = null)',
            '    {',
            '       return $this->get2DAttribute(\'db\', \'engine\', $default);',
            '    }'
        ];
        $this->assertRegExp($this->createRegExp($getFunction), $text);
        $getFunction = str_replace(['Engine','engine'],['Username','username'],$getFunction);
        $this->assertRegExp($this->createRegExp($getFunction), $text);
        $this->assertNotRegExp($this->createRegExp($this->get1DAttributeGetter()), $text);
        $this->assertRegExp($this->createRegExp($this->get2DAttributeGetter()), $text);
        $setFunction = [
            '    public function setDbEngine($dbEngine)',
            '    {',
            '        $this->data[\'db\'][\'engine\'] = $dbEngine;',
            '    }'
        ];
        $this->assertRegExp($this->createRegExp($setFunction), $text);
        $setFunction = str_replace(['Engine','engine'],['Username','username'],$setFunction);
        $this->assertRegExp($this->createRegExp($setFunction), $text);
    }

    public function testMixedArraySettersAndGettersOn()
    {
        $flattenArrayList = [
            'DbEngine' => ['db', 'engine'],
            'Keys' => ['keys'],
        ];
        $this->dtoTemplate->expects($this->once())->method('getGetters')->with($this->isTrue())->willReturn(true);
        $this->dtoTemplate->expects($this->once())->method('getSetters')->with($this->isTrue())->willReturn(true);
        $text = $this->subject->getClassText($this->dtoTemplate, $flattenArrayList);
        $this->assertRegExp($this->createRegExp('getDbEngine'), $text);
        $this->assertRegExp($this->createRegExp('getKeys'), $text);
        $this->assertRegExp($this->createRegExp('setDbEngine'), $text);
        $this->assertRegExp($this->createRegExp('setKeys'), $text);
        $this->assertRegExp($this->createRegExp($this->get1DAttributeGetter()), $text);
        $this->assertRegExp($this->createRegExp($this->get2DAttributeGetter()), $text);
    }

    private function createRegExp($array, $start = '', $end = ''){
        if(!is_array($array)){
            $array = [$array];
        }
        $search = str_split('?$[]()');
        $replace = [];
        foreach($search as $value){
            $replace[]='\\'.$value;
        }
        foreach($array as $key=>$value){
            $array[$key]=str_replace($search,$replace,$value);
        }
        return '/'.$start.implode('\s*\n',$array).$end.'/';
    }

    private function getTextGettersAndSettersOff(){
        return [
            '<?php',
            'namespace ns;',
            'class Cls',
            '{',
            '    private $data;',
            '    public function __construct($data = [])',
            '    {',
            '        $this->data = (array)$data;',
            '    }',
            '}'
        ];
    }

    private function get1DAttributeGetter()
    {
        return [
            '    private function getAttribute($key, $default)',
            '    {',
            '        if (is_array($this->data) && array_key_exists($key, $this->data)) {',
            '            return $this->data[$key];',
            '        } else {',
            '            return $default;',
            '        }',
            '    }',
        ];
    }

    private function get2DAttributeGetter()
    {
        return [
            '    private function get2DAttribute($key1, $key2, $default)',
            '    {',
            '        if (is_array($this->data[$key1]) && array_key_exists($key2, $this->data[$key1])) {',
            '            return $this->data[$key1][$key2];',
            '        } else {',
            '            return $default;',
            '        }',
            '    }'
        ];
    }
}
