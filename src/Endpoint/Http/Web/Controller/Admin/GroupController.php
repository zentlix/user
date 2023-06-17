<?php

declare(strict_types=1);

namespace Zentlix\User\Endpoint\Http\Web\Controller\Admin;

use Cycle\ORM\Select;
use Spiral\AdminPanel\Attribute\DataGrid;
use Spiral\AdminPanel\Resource\ListResource;
use Spiral\AdminPanel\Resource\UpdateResource;
use Spiral\Domain\Annotation\Guarded;
use Spiral\Domain\Annotation\GuardNamespace;
use Spiral\Http\Request\InputManager;
use Zentlix\Core\Endpoint\Http\Web\Controller\Admin\AbstractRenderController;
use Zentlix\User\Domain\Group\ReadModel\GroupView;
use Zentlix\User\Endpoint\Http\Web\Component\Admin\Group\UpdateComponent;
use Zentlix\User\Infrastructure\Group\ReadModel\Repository\CycleGroupRepository;

#[GuardNamespace('user_permissions.group')]
final class GroupController extends AbstractRenderController
{
    #[DataGrid(name: 'admin-groups')]
    #[Guarded(permission: 'view')]
    public function groups(CycleGroupRepository $groups, InputManager $request): ListResource|Select
    {
        if ($request->isAjax()) {
            return $groups->withLocalized();
        }

        return new ListResource(
            title: 'user.group.groups',
            grid: 'admin-groups',
            gridRoute: 'admin.group.list'
        );
    }

    #[Guarded(permission: 'update')]
    public function update(GroupView $group): UpdateResource
    {
        return new UpdateResource(
            title: $group->title->title,
            resourceIdentifier: $group->getId(),
            component: UpdateComponent::class
        );
    }
}
