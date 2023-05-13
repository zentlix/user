<?php

declare(strict_types=1);

namespace Zentlix\User\Endpoint\Http\Web\Controller\Admin;

use Zentlix\Core\Endpoint\Http\Web\Controller\Admin\AbstractRenderController;

final class AuthController extends AbstractRenderController
{
    public function login(): string
    {
        return $this->render('user:admin/security/sign-in', ['title' => $this->translator->trans('Login')]);
    }
}
