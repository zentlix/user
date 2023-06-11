<?php

declare(strict_types=1);

namespace Zentlix\User\Endpoint\Http\Web\EventListener;

use Spiral\AdminPanel\Icons;
use Spiral\Events\Attribute\Listener;
use Zentlix\Core\Domain\Menu\Event\OnBuildSidebar;

final class SidebarListener
{
    #[Listener]
    public function __invoke(OnBuildSidebar $event): void
    {
        $usersRoot = $event->menu->addChild('user.user.users')->setExtra('icon', Icons::o_user_group);

        $groups = $usersRoot
            ->addChild('user.group.groups', ['route' => 'admin.group.list'])
            ->setDisplayChildren(false);
        $groups->addChild('user.group.update', ['route' => 'admin.group.update']);

        $users = $usersRoot->addChild('user.user.users', ['route' => 'admin.user.list']);

        $locale = $event->menu
            ->getChild('core.settings')
            ?->addChild('user.locale.languages', ['route' => 'admin.locale.list'])
            ->setDisplayChildren(false);
        $locale->addChild('user.locale.update', ['route' => 'admin.locale.update']);
    }
}
