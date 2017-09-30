<?php


use Matters\Utilities\Containers\Symfony;
use Matters\Utilities\Generators\DtoGeneratorService;

include_once(__DIR__.'/../../vendor/autoload.php');
$container =Symfony::getInstance();
$container->compile();
/** @var DtoGeneratorService $dtoGeneratorService */
$dtoGeneratorService = $container->get(DtoGeneratorService::class);
$file = $dtoGeneratorService->process(getcwd().'/'.$argv[1]);
echo "file written to $file";