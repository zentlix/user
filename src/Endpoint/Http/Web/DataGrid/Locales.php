<?php

declare(strict_types=1);

namespace Zentlix\User\Endpoint\Http\Web\DataGrid;

use Spiral\AdminPanel\Attribute\GridSchema;
use Spiral\AdminPanel\DataGrid\Column\BoolColumn;
use Spiral\AdminPanel\DataGrid\Column\LinkColumn;
use Spiral\AdminPanel\DataGrid\Column\NumberColumn;
use Spiral\AdminPanel\DataGrid\Column\TextColumn;
use Spiral\AdminPanel\DataGrid\ColumnsConfigurator;
use Spiral\AdminPanel\DataGrid\DefaultsConfigurator;
use Spiral\AdminPanel\DataGrid\FiltersConfigurator;
use Spiral\AdminPanel\DataGrid\GridSchemaInterface;
use Spiral\AdminPanel\DataGrid\PaginatorConfigurator;
use Spiral\AdminPanel\DataGrid\SortersConfigurator;
use Spiral\DataGrid\Specification\Filter;
use Spiral\DataGrid\Specification\Sorter;

#[GridSchema('admin-locales')]
final class Locales implements GridSchemaInterface
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
                    'label' => 'core.uuid',
                    'title' => 'core.edit',
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
                    'label' => 'core.title',
                    'title' => 'core.edit',
                    'class' => 'item-title',
                    'data' => '{title}'
                ],
            )
            ->add('code', TextColumn::class, ['label' => 'user.locale.language_code'])
            ->add('country_code', TextColumn::class, [
                'label' => 'user.locale.country_code',
                'field' => 'countryCode'
            ])
            ->add('active', BoolColumn::class, [
                'label' => 'user.locale.language_active',
                'trueValue' => 'core.yes',
                'falseValue' => 'core.no'
            ])
            ->add('sort', NumberColumn::class, ['label' => 'core.sort']);
    }

    public function filters(FiltersConfigurator $grid): void
    {
        $grid
            ->add(
                'search',
                new Filter\Any(
                    new Filter\Like('title'),
                    new Filter\Like('code'),
                    new Filter\Like('country_code')
                )
            );
    }

    public function sorters(SortersConfigurator $grid): void
    {
        $grid
            ->add('title', new Sorter\Sorter('title'))
            ->add('code', new Sorter\Sorter('code'))
            ->add('country_code', new Sorter\Sorter('country_code'))
            ->add('sort', new Sorter\Sorter('sort'));
    }

    public function paginator(PaginatorConfigurator $grid): void
    {
        $grid->configure(25, [10, 25, 50, 100]);
    }

    public function defaults(DefaultsConfigurator $grid): void
    {
        $grid->configure(['sort' => ['active' => 'asc', 'sort' => 'asc']]);
    }
}
