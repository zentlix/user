<?php

declare(strict_types=1);

namespace Zentlix\User\Endpoint\Http\Web\Controller\Admin;

use Cycle\ORM\Select;
use Spiral\AdminPanel\Resource\ListResource;
use Spiral\AdminPanel\Resource\UpdateResource;
use Spiral\DataGrid\Annotation\DataGrid;
use Zentlix\Core\Endpoint\Http\Web\Controller\Admin\AbstractRenderController;
use Zentlix\User\Infrastructure\Locale\ReadModel\Repository\CycleLocaleRepository;

final class LocaleController extends AbstractRenderController
{
    public function locales(): ListResource
    {
        return new ListResource(
            title: 'user.locale.languages',
            grid: 'admin-locales',
            gridRoute: 'admin.locale.grid'
        );
    }

    public function update(): UpdateResource
    {
        return new UpdateResource(
            title: 'user.locale.languages'
        );
    }

    #[DataGrid(grid: 'admin-locales')]
    public function grid(CycleLocaleRepository $locales): Select
    {
        return $locales->select();
    }
}
