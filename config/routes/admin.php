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
        ->methods(['GET', 'POST']);
    $routes
        ->add('group.create', '/group/create')
        ->action(GroupController::class, 'create')
        ->methods(['GET', 'POST']);
    $routes
        ->add('group.update', '/group/<group>/update')
        ->action(GroupController::class, 'update')
        ->methods(['GET', 'POST']);
    $routes
        ->add('group.delete', '/group/<group>/delete')
        ->action(GroupController::class, 'delete')
        ->methods('DELETE');

    $routes
        ->add('locale.list', '/locales')
        ->action(LocaleController::class, 'locales')
        ->methods(['GET', 'POST']);
    $routes
        ->add('locale.update', '/locales/<locale>/update')
        ->action(LocaleController::class, 'update')
        ->methods(['GET', 'POST']);

    $routes
        ->add('user.list', '/users')
        ->action(UserController::class, 'users')
        ->methods(['GET', 'POST']);
    $routes
        ->add('user.create', '/user/create')
        ->action(UserController::class, 'create')
        ->methods(['GET', 'POST']);
    $routes
        ->add('user.update', '/user/<user>/update')
        ->action(UserController::class, 'update')
        ->methods(['GET', 'POST']);
    $routes
        ->add('user.delete', '/user/<user>/delete')
        ->action(UserController::class, 'delete')
        ->methods('DELETE');
};
