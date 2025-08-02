<?php

declare(strict_types=1);

namespace BearinUser\Module;

use BEAR\Package\AbstractAppModule;
use BEAR\Package\PackageModule;
use Ray\AuraSqlModule\AuraSqlModule;
use Ray\AuraSessionModule\AuraSessionModule;
use Ray\ValidateModule\ValidateModule;
use Ray\RoleModule\RoleModule;

class AppModule extends AbstractAppModule
{
    protected function configure(): void
    {
        $this->install(new PackageModule());
        $this->install(new AuraSqlModule('mysql:host=localhost;dbname=bearin_user', 'root', ''));
        $this->install(new AuraSessionModule());
        $this->install(new ValidateModule());
        $this->install(new RoleModule());
        $this->install(new UserModule());
    }
}
