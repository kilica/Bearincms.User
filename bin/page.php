#!/usr/bin/env php
<?php

declare(strict_types=1);

use BEAR\Package\Bootstrap;
use BearinUser\Module\AppModule;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$page = (new Bootstrap())->getApp(AppModule::class, $GLOBALS['_ENV']['TMP_DIR'] ?? __DIR__ . '/../var/tmp', 'page-app');
$request = $page->router->match($GLOBALS, $_SERVER);

try {
    $page = $page->resource->get($request->path, $request->query);
    $page->transfer($page->renderer, $_SERVER);
    exit(0);
} catch (Exception $e) {
    $page->error = $e;
    $page->transfer($page->errorRenderer, $_SERVER);
    exit(1);
}
