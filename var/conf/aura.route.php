<?php

declare(strict_types=1);

use Aura\Router\Map;

return function (Map $map): void {
    $map->route('user.list', 'GET', '/users')
        ->defaults(['path' => '/user']);
    
    $map->route('user.get', 'GET', '/users/{id}')
        ->defaults(['path' => '/user'])
        ->tokens(['id' => '\d+']);
    
    $map->route('user.create', 'POST', '/users')
        ->defaults(['path' => '/user']);
    
    $map->route('user.update', 'PUT', '/users/{id}')
        ->defaults(['path' => '/user'])
        ->tokens(['id' => '\d+']);
    
    $map->route('user.delete', 'DELETE', '/users/{id}')
        ->defaults(['path' => '/user'])
        ->tokens(['id' => '\d+']);
    
    $map->route('auth.login', 'POST', '/auth/login')
        ->defaults(['path' => '/auth']);
    
    $map->route('auth.logout', 'DELETE', '/auth/logout')
        ->defaults(['path' => '/auth']);
    
    $map->route('password.reset.request', 'POST', '/password-reset')
        ->defaults(['path' => '/password-reset']);
    
    $map->route('password.reset.confirm', 'PUT', '/password-reset')
        ->defaults(['path' => '/password-reset']);
    
    $map->route('profile.get', 'GET', '/profile/{userId}')
        ->defaults(['path' => '/profile'])
        ->tokens(['userId' => '\d+']);
    
    $map->route('profile.update', 'PUT', '/profile/{userId}')
        ->defaults(['path' => '/profile'])
        ->tokens(['userId' => '\d+']);
};
