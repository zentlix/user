<?php

declare(strict_types=1);

namespace Zentlix\User\Endpoint\Http\Web\DataGrid;

use Spiral\AdminPanel\Attribute\GridSchema;
use Spiral\AdminPanel\DataGrid\Column\LinkColumn;
use Spiral\AdminPanel\DataGrid\Column\MapColumn;
use Spiral\AdminPanel\DataGrid\Column\NumberColumn;
use Spiral\AdminPanel\DataGrid\Column\TextColumn;
use Spiral\AdminPanel\DataGrid\ColumnsConfigurator;
use Spiral\AdminPanel\DataGrid\DefaultsConfigurator;
use Spiral\AdminPanel\DataGrid\FiltersConfigurator;
use Spiral\AdminPanel\DataGrid\SortersConfigurator;
use Spiral\DataGrid\Specification\Filter;
use Spiral\DataGrid\Specification\Sorter\Sorter;
use Zentlix\Core\Endpoint\Http\Web\DataGrid\AbstractGridSchema;
use Zentlix\User\Domain\Group\ReadModel\GroupView;
use Zentlix\User\Domain\Group\Role;

#[GridSchema('admin-groups')]
final class Groups extends AbstractGridSchema
{
    public function columns(ColumnsConfigurator $grid): void
    {
        $grid
            ->add(
                'uuid',
                LinkColumn::class,
                [
                    'route' => 'admin.group.update',
                    'routeParameters' => ['group' => '{uuid}'],
                    'label' => 'user.uuid',
                    'title' => 'user.edit',
                    'class' => 'item-title',
                    'data' => '{uuid}'
                ],
            )
            ->add(
                'title',
                LinkColumn::class,
                [
                    'route' => 'admin.group.update',
                    'routeParameters' => ['group' => '{uuid}'],
                    'label' => 'user.title',
                    'title' => 'user.edit',
                    'class' => 'item-title',
                    'data' => fn (GroupView $row): string => $row->title?->title
                ],
            )
            ->add('code', TextColumn::class, ['label' => 'user.symbol_code'])
            ->add('role', MapColumn::class, [
                'label' => 'user.group.group_access',
                'map' => [
                    Role::User->value => 'user.group.user_role',
                    Role::Admin->value => 'user.group.admin_role'
                ]
            ])
            ->add('sort', NumberColumn::class, ['label' => 'user.sort']);
    }

    public function filters(FiltersConfigurator $grid): void
    {
        $grid
            ->search(new Filter\Any(
                new Filter\Like('title'),
                new Filter\Like('code'),
                new Filter\Like('country_code')
            ));
    }

    public function sorters(SortersConfigurator $grid): void
    {
        $grid->add('uuid', new Sorter('uuid'));
        $grid->add('title', new Sorter('title.title'));
        $grid->add('code', new Sorter('code'));
        $grid->add('role', new Sorter('role'));
        $grid->add('sort', new Sorter('sort'));
    }

    public function defaults(DefaultsConfigurator $grid): void
    {
        $grid->configure(['sort' => ['sort' => 'asc']]);
    }
}
