<?php


use Matters\Utilities\Containers\Symfony;
use Matters\Utilities\Generators\DtoGeneratorService;

include_once('../../vendor/autoload.php');
$container =Symfony::getInstance();
$container->compile();
/** @var DtoGeneratorService $dtoGeneratorService */
$dtoGeneratorService = $container->get(DtoGeneratorService::class);
$dtoGeneratorService->process(realpath(__DIR__).'/'.$argv[1]);
