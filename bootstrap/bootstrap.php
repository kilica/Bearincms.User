<?php

declare(strict_types=1);

use BEAR\Package\Bootstrap;
use BearinUser\Module\AppModule;

require_once dirname(__DIR__) . '/vendor/autoload.php';

return (new Bootstrap())->getApp(AppModule::class, $GLOBALS['_ENV']['TMP_DIR'] ?? __DIR__ . '/../var/tmp');
