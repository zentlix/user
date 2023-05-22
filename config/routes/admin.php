<?php

declare(strict_types=1);

use Spiral\Router\Loader\Configurator\RoutingConfigurator;
use Zentlix\User\Endpoint\Http\Web\Controller\Admin\GroupController;
use Zentlix\User\Endpoint\Http\Web\Controller\Admin\LocaleController;
use Zentlix\User\Endpoint\Http\Web\Controller\Admin\UserController;

return static function (RoutingConfigurator $routes): void {
    $routes
        ->add('group.list', '/groups')
        ->action(GroupController::class, 'groups')
        ->methods('GET');

    $routes
        ->add('locale.list', '/locales')
        ->action(LocaleController::class, 'locales')
        ->methods('GET');
    $routes
        ->add('locale.grid', '/locales/list')
        ->action(LocaleController::class, 'grid')
        ->methods('POST');
    $routes
        ->add('locale.update', '/locales/<locale>/update')
        ->action(LocaleController::class, 'update')
        ->methods('GET');

    $routes
        ->add('user.list', '/users')
        ->action(UserController::class, 'users')
        ->methods('GET');
};
