<?php

declare(strict_types=1);

use Spiral\Router\Loader\Configurator\RoutingConfigurator;
use Zentlix\User\Endpoint\Http\Web\Controller\Admin\AuthController;

return static function (RoutingConfigurator $routes): void {
    $routes
        ->add('admin.login', '/admin/sign-in')
        ->action(AuthController::class, 'login')
        ->methods(['GET', 'POST']);
};
