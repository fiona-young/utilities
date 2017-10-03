<?php

namespace Matters\Utilities\Generators;

use Matters\Utilities\Dtos\EnumTemplate;
use Matters\Utilities\Exceptions\UtilitiesException;
use Matters\Utilities\Services\EnumClassWriterService;
use Matters\Utilities\Services\FileService;
use Matters\Utilities\Services\ProcessEnumTemplate;
use Matters\Utilities\Services\VerifyEnumTemplate;

class EnumGeneratorService
{
    private $fileService;
    private $verifyEnumTemplate;
    private $processEnumTemplate;
    private $classWriter;

    public function __construct(
        FileService $fileService,
        VerifyEnumTemplate $verifyEnumTemplate,
        ProcessEnumTemplate $processEnumTemplate,
        EnumClassWriterService $classWriter
    )
    {
        $this->fileService = $fileService;
        $this->verifyEnumTemplate = $verifyEnumTemplate;
        $this->processEnumTemplate = $processEnumTemplate;
        $this->classWriter = $classWriter;
    }

    public function loadFile($fileLocation)
    {
        if(is_null($fileLocation)){
            throw new UtilitiesException("no file selected");
        }
        $fileLocation = $this->fileService->getFromRunningDirectory($fileLocation);
        $data = $this->fileService->getDecodedJsonFromFile($fileLocation);
        $enumTemplate = new EnumTemplate($data);
        $dir = $this->fileService->getDirectory($fileLocation);
        $enumTemplate->setDirectory($dir.$enumTemplate->getDirectory());
        return $enumTemplate;
    }

    public function process($fileLocation){
        $enumTemplate = $this->loadFile($fileLocation);
        $this->verifyEnumTemplate->verify($enumTemplate);
        $enumTemplate = $this->processEnumTemplate->process($enumTemplate);
        $classText = $this->classWriter->getClassText($enumTemplate);
        $fileName = $enumTemplate->getDirectory()."/".$enumTemplate->getClassName().".php";
        $this->fileService->writeFile($fileName, $classText);
        return $fileName;
    }

}