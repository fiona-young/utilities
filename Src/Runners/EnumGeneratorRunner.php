<?php


use Matters\Utilities\Containers\Symfony;
use Matters\Utilities\Generators\EnumGeneratorService;

include_once(__DIR__.'/../../vendor/autoload.php');
$container =Symfony::getInstance();
$container->compile();
/** @var EnumGeneratorService $generatorService */
$generatorService = $container->get(EnumGeneratorService::class);
$file = $generatorService->process(@$argv[1]);
echo "file written to $file";