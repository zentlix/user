<?php

declare(strict_types=1);

namespace Zentlix\User\Endpoint\Http\Web\Controller\Admin;

use Cycle\ORM\Select;
use Spiral\AdminPanel\Attribute\DataGrid;
use Spiral\AdminPanel\Resource\ListResource;
use Spiral\AdminPanel\Resource\UpdateResource;
use Spiral\Http\Request\InputManager;
use Zentlix\Core\Endpoint\Http\Web\Controller\Admin\AbstractRenderController;
use Zentlix\User\Domain\Locale\ReadModel\LocaleView;
use Zentlix\User\Endpoint\Http\Web\Component\Admin\Locale\UpdateComponent;
use Zentlix\User\Infrastructure\Locale\ReadModel\Repository\CycleLocaleRepository;

final class LocaleController extends AbstractRenderController
{
    #[DataGrid(name: 'admin-locales')]
    public function locales(CycleLocaleRepository $locales, InputManager $request): ListResource|Select
    {
        if ($request->isAjax()) {
            return $locales->select();
        }

        return new ListResource(
            title: 'user.locale.languages',
            grid: 'admin-locales',
            gridRoute: 'admin.locale.list'
        );
    }

    public function update(LocaleView $locale): UpdateResource
    {
        return new UpdateResource(
            title: $locale->title,
            resourceIdentifier: $locale->getId(),
            component: UpdateComponent::class,
            successMessage: 'user.locale.updated_successfully',
            redirectTo: (string) $this->router->uri('admin.locale.list')
        );
    }
}
