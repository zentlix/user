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
        $users = $event->menu->addChild('user.user.users')->setExtra('icon', Icons::o_user_group);

        $users->addChild('user.group.groups', ['route' => 'admin.group.list']);
        $users->addChild('user.user.users', ['route' => 'admin.user.list']);

        $event->menu
            ->getChild('core.settings')
            ?->addChild('user.locale.languages', ['route' => 'admin.locale.list']);
    }
}
