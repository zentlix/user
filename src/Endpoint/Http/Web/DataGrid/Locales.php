<?php

declare(strict_types=1);

namespace Zentlix\User\Endpoint\Http\Web\DataGrid;

use Spiral\AdminPanel\Attribute\GridSchema;
use Spiral\AdminPanel\DataGrid\Column\BadgeColumn;
use Spiral\AdminPanel\DataGrid\Column\LinkColumn;
use Spiral\AdminPanel\DataGrid\Column\NumberColumn;
use Spiral\AdminPanel\DataGrid\Column\TextColumn;
use Spiral\AdminPanel\DataGrid\ColumnsConfigurator;
use Spiral\AdminPanel\DataGrid\DefaultsConfigurator;
use Spiral\AdminPanel\DataGrid\FiltersConfigurator;
use Spiral\AdminPanel\DataGrid\SortersConfigurator;
use Spiral\DataGrid\Specification\Filter;
use Spiral\DataGrid\Specification\Sorter;
use Spiral\DataGrid\Specification\Value\StringValue;
use Zentlix\Core\Endpoint\Http\Web\DataGrid\AbstractGridSchema;
use Zentlix\User\Domain\Locale\ReadModel\LocaleView;

#[GridSchema('admin-locales')]
final class Locales extends AbstractGridSchema
{
    public function columns(ColumnsConfigurator $grid): void
    {
        $grid
            ->add(
                'uuid',
                LinkColumn::class,
                [
                    'route' => 'admin.locale.update',
                    'routeParameters' => ['locale' => '{uuid}'],
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
                    'route' => 'admin.locale.update',
                    'routeParameters' => ['locale' => '{uuid}'],
                    'label' => 'user.title',
                    'title' => 'user.edit',
                    'class' => 'item-title',
                    'data' => '{title}'
                ],
            )
            ->add('code', TextColumn::class, ['label' => 'user.locale.language_code'])
            ->add('country_code', TextColumn::class, [
                'label' => 'user.locale.country_code',
                'field' => 'countryCode'
            ])
            ->add('active', BadgeColumn::class, [
                'conditions' => [
                    'user.locale.language_active' => 'info',
                    'user.locale.language_inactive' => 'danger'
                ],
                'label' => 'user.locale.language_status',
                'data' => fn (LocaleView $row): string => $row->active
                    ? 'user.locale.language_active'
                    : 'user.locale.language_inactive'
            ])
            ->add('sort', NumberColumn::class, ['label' => 'user.sort']);
    }

    public function filters(FiltersConfigurator $grid): void
    {
        $grid
            ->search(new Filter\Any(
                new Filter\Like('uuid'),
                new Filter\Like('title'),
                new Filter\Like('code'),
                new Filter\Like('country_code')
            ));

        $grid->filter(
            'active',
            new Filter\Equals('active', new StringValue()),
            'user.locale.language_active',
            [null => 'user.all', 1 => 'user.locale.language_active', 0 => 'user.locale.language_inactive']
        );
    }

    public function sorters(SortersConfigurator $grid): void
    {
        $grid
            ->add('uuid', new Sorter\Sorter('uuid'))
            ->add('title', new Sorter\Sorter('title'))
            ->add('code', new Sorter\Sorter('code'))
            ->add('country_code', new Sorter\Sorter('country_code'))
            ->add('active', new Sorter\Sorter('active'))
            ->add('sort', new Sorter\Sorter('sort'));
    }

    public function defaults(DefaultsConfigurator $grid): void
    {
        $grid->configure(['sort' => ['sort' => 'asc']]);
    }
}
