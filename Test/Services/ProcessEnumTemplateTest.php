<?php

namespace Matters\Utilities\Test\Services;

use Matters\Utilities\Dtos\EnumTemplate;
use Matters\Utilities\Services\ProcessEnumTemplate;
use Matters\Utilities\Test\TestCase;

class ProcessEnumTemplateTest extends TestCase
{
    /** @var  ProcessEnumTemplate */
    private $subject;

    public function setUp()
    {

        $this->subject = new ProcessEnumTemplate();
    }

    public function test_methodOnly()
    {
        $enumData = [
            "enumKeys" => [],
            "enumFindBy" => [],
            "enumData" => ["ADD" => [], "UPDATE" => [], "DELETE" => []],
            "className" => "ConstructorTypes",
            "namespace" => 'Matters\\Utilities\\Enums',
            "directory" => '../../Src/Enums'
        ];
        $methodName = ProcessEnumTemplate::METHOD_NAME;
        $enumTemplate = new EnumTemplate($enumData);
        $processedTemplate = $this->subject->process($enumTemplate);
        $this->assertEquals($enumData['directory'], $processedTemplate->getDirectory());
        $this->assertEquals($enumData['className'], $processedTemplate->getClassName());
        $this->assertEquals($enumData['namespace'], $processedTemplate->getNamespace());
        $this->assertEquals([
            "ADD" => [$methodName => "'ADD'"],
            "UPDATE" => [$methodName => "'UPDATE'"],
            "DELETE" => [$methodName => "'DELETE'"]
        ], $processedTemplate->getEnumData());
        $this->assertEquals([$methodName], $processedTemplate->getEnumFindBy());
        $this->assertEquals([$methodName], $processedTemplate->getEnumKeys());
        $this->assertEquals([$methodName=>["'ADD'","'UPDATE'","'DELETE'"]], $processedTemplate->getEnumTranspose());
        $this->assertEquals(["ADD","UPDATE","DELETE"], $processedTemplate->getEnumTypes());
    }

    public function test_methodIdAndName()
    {
        $enumData = [
            "enumKeys" => [],
            "enumFindBy" => [],
            "enumData" => [
                "MSP_SSO" => ['msp_sso', "Turn on"],
                "RISK_INTELLIGENCE" => [15, "_('Risk Intelligence')"],
                "DEVICE_FILTERS" => ['device filters', "_('Device Filters)"]
            ],
            "className" => "ConstructorTypes",
            "namespace" => 'Matters\\Utilities\\Enums',
            "directory" => '../../Src/Enums'
        ];
        $methodName = ProcessEnumTemplate::METHOD_NAME;
        $enumTemplate = new EnumTemplate($enumData);
        $processedTemplate = $this->subject->process($enumTemplate);
        $this->assertEquals($enumData['directory'], $processedTemplate->getDirectory());
        $this->assertEquals($enumData['className'], $processedTemplate->getClassName());
        $this->assertEquals($enumData['namespace'], $processedTemplate->getNamespace());
        $this->assertEquals([
            "ADD" => [$methodName => "'ADD'"],
            "UPDATE" => [$methodName => "'UPDATE'"],
            "DELETE" => [$methodName => "'DELETE'"]
        ], $processedTemplate->getEnumData());
        $this->assertEquals([$methodName], $processedTemplate->getEnumFindBy());
        $this->assertEquals([$methodName], $processedTemplate->getEnumKeys());
        $this->assertEquals([$methodName=>["'ADD'","'UPDATE'","'DELETE'"]], $processedTemplate->getEnumTranspose());
        $this->assertEquals(["ADD","UPDATE","DELETE"], $processedTemplate->getEnumTypes());
    }


}