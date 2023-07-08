<?php

declare(strict_types=1);

namespace Zentlix\User\Endpoint\Http\Web\Controller\Admin;

use Cycle\ORM\Select;
use Psr\Http\Message\ResponseInterface;
use Spiral\AdminPanel\Attribute\DataGrid;
use Spiral\Domain\Annotation\Guarded;
use Spiral\Domain\Annotation\GuardNamespace;
use Zentlix\Core\Domain\Shared\Exception\DomainException;
use Zentlix\Core\Endpoint\Http\Web\Controller\Admin\AbstractAdminController;
use Zentlix\User\Application\Locale\Command\UpdateCommand;
use Zentlix\User\Domain\Locale\DataTransferObject\Locale;
use Zentlix\User\Domain\Locale\ReadModel\LocaleView;
use Zentlix\User\Endpoint\Http\Web\Form\Admin\Locale\UpdateForm;
use Zentlix\User\Infrastructure\Locale\ReadModel\Repository\CycleLocaleRepository;

#[GuardNamespace('user_permissions.locale')]
final class LocaleController extends AbstractAdminController
{
    #[DataGrid(name: 'admin-locales')]
    #[Guarded(permission: 'view')]
    public function locales(CycleLocaleRepository $locales): string|Select
    {
        if ($this->input->isAjax()) {
            return $locales->select();
        }

        return $this->render('user:admin/locale/list');
    }

    #[Guarded(permission: 'update')]
    public function update(LocaleView $locale): string|ResponseInterface
    {
        try {
            $form = $this->formFactory->create(UpdateForm::class, Locale::fromLocale($locale));
            $form->handleRequest($this->input);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->exec(new UpdateCommand($form->getData()));
                $this->addFlash('success', 'user.locale.updated_successfully');
                return $this->redirectToRoute('admin.locale.list');
            }
        } catch (DomainException $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('admin.locale.update', ['locale' => $locale->uuid->toString()]);
        }

        return $this->render('user:admin/locale/update', [
            'form' => $form->createView(),
            'title' => $locale->title,
            'uuid'  => $locale->uuid->toString()
        ]);
    }
}
