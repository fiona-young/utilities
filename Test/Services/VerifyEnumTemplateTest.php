<?php

namespace Matters\Utilities\Test\Services;

use Matters\Utilities\Dtos\EnumTemplate;
use Matters\Utilities\Exceptions\UtilitiesException;
use Matters\Utilities\Services\VerifyEnumTemplate;
use Matters\Utilities\Test\TestCase;

class VerifyEnumTemplateTest extends TestCase
{
    /** @var  VerifyEnumTemplate */
    private $subject;

    public function setUp()
    {

        $this->subject = new VerifyEnumTemplate();
    }

    /**
     * @expectedException \Matters\Utilities\Exceptions\UtilitiesException
     * @expectedExceptionMessage Error VerifyEnumTemplate: directory is not a string ()
     */
    public function test_emptyTemplate()
    {
        $enumTemplate = new EnumTemplate();
        $this->subject->verify($enumTemplate);
    }


    /**
     * @expectedException \Matters\Utilities\Exceptions\UtilitiesException
     * @expectedExceptionMessage Error VerifyEnumTemplate: directory is not a string (134)
     */
    public function test_nonStringDirectory()
    {
        $enumData = [
            "enumKeys" => [],
            "enumFindBy" => [],
            "enumData" => [],
            "className" => "ConstructorTypes",
            "namespace" => 'Matters\\Utilities\\Enums',
            "directory" => '134'
        ];;
        $enumTemplate = new EnumTemplate($enumData);
        $this->subject->verify($enumTemplate);
    }

    /**
     * @expectedException \Matters\Utilities\Exceptions\UtilitiesException
     * @expectedExceptionMessage Error VerifyEnumTemplate: namespace is not a string (567)
     */
    public function test_nonStringNamespace()
    {
        $enumData = [
            "enumKeys" => [],
            "enumFindBy" => [],
            "enumData" => [],
            "className" => "ConstructorTypes",
            "namespace" => '567',
            "directory" => '../../Src/Enums'
        ];;
        $enumTemplate = new EnumTemplate($enumData);
        $this->subject->verify($enumTemplate);
    }


    /**
     * @expectedException \Matters\Utilities\Exceptions\UtilitiesException
     * @expectedExceptionMessage Error VerifyEnumTemplate: className is not a string ()
     */
    public function test_nonStringClassName()
    {
        $enumData = [
            "enumKeys" => [],
            "enumFindBy" => [],
            "enumData" => [],
            "className" => '',
            "namespace" => 'Matters\\Utilities\\Enums',
            "directory" => '../../Src/Enums'
        ];;
        $enumTemplate = new EnumTemplate($enumData);
        $this->subject->verify($enumTemplate);
    }

    /**
     * @expectedException \Matters\Utilities\Exceptions\UtilitiesException
     * @expectedExceptionMessage Error VerifyEnumTemplate: enumData is not a array (blue)
     */
    public function test_enumDataNotArray()
    {
        $enumData = [
            "enumKeys" => [],
            "enumFindBy" => [],
            "enumData" => "blue",
            "className" => 'ConstructorTypes',
            "namespace" => 'Matters\\Utilities\\Enums',
            "directory" => '../../Src/Enums'
        ];;
        $enumTemplate = new EnumTemplate($enumData);
        $this->subject->verify($enumTemplate);
    }

    /**
     * @expectedException \Matters\Utilities\Exceptions\UtilitiesException
     * @expectedExceptionMessage Error VerifyEnumTemplate: enumFindBy is not a array (12)
     */
    public function test_enumFindByNotArray()
    {
        $enumData = [
            "enumKeys" => [],
            "enumFindBy" => 12,
            "enumData" => [],
            "className" => 'ConstructorTypes',
            "namespace" => 'Matters\\Utilities\\Enums',
            "directory" => '../../Src/Enums'
        ];;
        $enumTemplate = new EnumTemplate($enumData);
        $this->subject->verify($enumTemplate);
    }

    /**
     * @expectedException \Matters\Utilities\Exceptions\UtilitiesException
     * @expectedExceptionMessage Error VerifyEnumTemplate: enumKeys is not a array (red and blue)
     */
    public function test_enumKeysNotArray()
    {
        $enumData = [
            "enumKeys" => 'red and blue',
            "enumFindBy" => [],
            "enumData" => [],
            "className" => 'ConstructorTypes',
            "namespace" => 'Matters\\Utilities\\Enums',
            "directory" => '../../Src/Enums'
        ];;
        $enumTemplate = new EnumTemplate($enumData);
        $this->subject->verify($enumTemplate);
    }

    /**
     * @expectedException \Matters\Utilities\Exceptions\UtilitiesException
     * @expectedExceptionMessage Error VerifyEnumTemplate: enumData is empty array
     */
    public function test_enumDataEmptyArray()
    {
        $enumData = [
            "enumKeys" => [],
            "enumFindBy" => [],
            "enumData" => [],
            "className" => 'ConstructorTypes',
            "namespace" => 'Matters\\Utilities\\Enums',
            "directory" => '../../Src/Enums'
        ];;
        $enumTemplate = new EnumTemplate($enumData);
        $this->subject->verify($enumTemplate);
    }

    /**
     * @expectedException \Matters\Utilities\Exceptions\UtilitiesException
     * @expectedExceptionMessage Error VerifyEnumTemplate: enumData is 1D array
     */
    public function test_enumData1DArray()
    {
        $enumData = [
            "enumKeys" => [],
            "enumFindBy" => [],
            "enumData" => ["ADD" , "UPDATE" , "DELETE" ],
            "className" => 'ConstructorTypes',
            "namespace" => 'Matters\\Utilities\\Enums',
            "directory" => '../../Src/Enums'
        ];;
        $enumTemplate = new EnumTemplate($enumData);
        $this->subject->verify($enumTemplate);
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