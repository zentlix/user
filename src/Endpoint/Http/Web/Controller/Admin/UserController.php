<?php

declare(strict_types=1);

namespace Zentlix\User\Endpoint\Http\Web\Controller\Admin;

use Cycle\ORM\Select;
use Psr\Http\Message\ResponseInterface;
use Spiral\AdminPanel\Attribute\DataGrid;
use Spiral\Domain\Annotation\Guarded;
use Spiral\Domain\Annotation\GuardNamespace;
use Zentlix\Core\Endpoint\Http\Web\Controller\Admin\AbstractRenderController;
use Zentlix\User\Domain\User\ReadModel\UserView;
use Zentlix\User\Infrastructure\User\ReadModel\Repository\CycleUserRepository;

#[GuardNamespace('user_permissions.user')]
final class UserController extends AbstractRenderController
{
    #[DataGrid(name: 'admin-users')]
    #[Guarded(permission: 'view')]
    public function users(CycleUserRepository $users): string|Select
    {
        if ($this->input->isAjax()) {
            return $users->select();
        }

        return $this->render('user:admin/user/list');
    }

    #[Guarded(permission: 'create')]
    public function create(): string|ResponseInterface
    {
    }

    #[Guarded(permission: 'update')]
    public function update(UserView $user): string|ResponseInterface
    {
    }

    #[Guarded(permission: 'delete')]
    public function delete(UserView $user): ResponseInterface
    {
    }
}
