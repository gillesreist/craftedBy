<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Cast\RecastingRemovalRector;
use Rector\Php54\Rector\Array_\LongArrayToShortArrayRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;
use RectorLaravel\Rector\MethodCall\RedirectRouteToToRouteHelperRector;
use RectorLaravel\Set\LaravelSetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/app',
        __DIR__ . '/bootstrap',
        __DIR__ . '/config',
        __DIR__ . '/public',
        __DIR__ . '/resources',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
    ])

    ->withSets([
        SetList::DEAD_CODE,
        SetList::CODING_STYLE,
        LevelSetList::UP_TO_PHP_83,
        LaravelSetList::LARAVEL_100,
    ])

    ->withRules([
        AddVoidReturnTypeWhereNoReturnRector::class,
        RedirectRouteToToRouteHelperRector::class,
    ])

    ->withSkip([
        RecastingRemovalRector::class,
        LongArrayToShortArrayRector::class,
    ]);


