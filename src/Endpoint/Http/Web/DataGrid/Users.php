<?php

declare(strict_types=1);

namespace Zentlix\User\Endpoint\Http\Web\DataGrid;

use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Spiral\AdminPanel\Attribute\GridSchema;
use Spiral\AdminPanel\DataGrid\Column\LinkColumn;
use Spiral\AdminPanel\DataGrid\Column\TextColumn;
use Spiral\AdminPanel\DataGrid\ColumnsConfigurator;
use Spiral\AdminPanel\DataGrid\DefaultsConfigurator;
use Spiral\AdminPanel\DataGrid\FiltersConfigurator;
use Spiral\AdminPanel\DataGrid\SortersConfigurator;
use Spiral\DataGrid\Specification\Filter;
use Spiral\DataGrid\Specification\Sorter\Sorter;
use Zentlix\Core\Endpoint\Http\Web\DataGrid\AbstractGridSchema;
use Zentlix\User\Domain\User\ReadModel\UserView;

#[GridSchema('admin-users')]
final class Users extends AbstractGridSchema
{
    public function columns(ColumnsConfigurator $grid): void
    {
        $grid
            ->add(
                'uuid',
                LinkColumn::class,
                [
                    'route' => 'admin.user.update',
                    'routeParameters' => ['user' => '{uuid}'],
                    'label' => 'user.uuid',
                    'title' => 'user.edit',
                    'class' => 'item-title',
                    'data' => '{uuid}'
                ],
            )
            ->add(
                'email',
                LinkColumn::class,
                [
                    'route' => 'admin.user.update',
                    'routeParameters' => ['user' => '{uuid}'],
                    'label' => 'user.email',
                    'title' => 'user.edit',
                    'class' => 'item-title'
                ],
            )
            ->add('phone', TextColumn::class, [
                'label' => 'user.phone',
                'data' => fn (UserView $row): string => $row->phone !== null
                    ? PhoneNumberUtil::getInstance()->format($row->phone, PhoneNumberFormat::E164)
                    : ''
            ])
            ->add('firstName', TextColumn::class, ['label' => 'user.user.first_name'])
            ->add('lastName', TextColumn::class, ['label' => 'user.user.last_name'])
        ;
    }

    public function filters(FiltersConfigurator $grid): void
    {
        $grid
            ->search(new Filter\Any(
                new Filter\Like('email'),
                new Filter\Like('phone'),
                new Filter\Like('firstName'),
                new Filter\Like('lastName')
            ));
    }

    public function sorters(SortersConfigurator $grid): void
    {
        $grid->add('uuid', new Sorter('uuid'));
        $grid->add('email', new Sorter('email'));
        $grid->add('phone', new Sorter('phone'));
        $grid->add('firstName', new Sorter('firstName'));
        $grid->add('lastName', new Sorter('lastName'));
    }

    public function defaults(DefaultsConfigurator $grid): void
    {
        $grid->configure(['sort' => ['createdAt' => 'desc']]);
    }
}
