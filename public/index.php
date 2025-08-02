<?php

declare(strict_types=1);

use BEAR\Package\Bootstrap;
use BearinUser\Module\AppModule;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$app = (new Bootstrap())->getApp(AppModule::class, $GLOBALS['_ENV']['TMP_DIR'] ?? __DIR__ . '/../var/tmp', 'prod-html-app');
$request = $app->router->match($GLOBALS, $_SERVER);

try {
    $page = $app->resource->get($request->path, $request->query);
    $page->transfer($app->responder, $_SERVER);
} catch (Exception $e) {
    http_response_code(500);
    echo $e->getMessage();
}
