<?php

declare(strict_types=1);

namespace Zentlix\User\Endpoint\Http\Web\Controller\Admin;

use Cycle\ORM\Select;
use Psr\Http\Message\ResponseInterface;
use Spiral\AdminPanel\Attribute\DataGrid;
use Spiral\Domain\Annotation\Guarded;
use Spiral\Domain\Annotation\GuardNamespace;
use Zentlix\Core\Domain\Shared\Exception\DomainException;
use Zentlix\Core\Endpoint\Http\Web\Controller\Admin\AbstractRenderController;
use Zentlix\User\Application\Group\Command\CreateCommand;
use Zentlix\User\Application\Group\Command\DeleteCommand;
use Zentlix\User\Application\Group\Command\UpdateCommand;
use Zentlix\User\Domain\Group\DataTransferObject\Group;
use Zentlix\User\Domain\Group\ReadModel\GroupView;
use Zentlix\User\Endpoint\Http\Web\Form\Admin\Group\CreateForm;
use Zentlix\User\Endpoint\Http\Web\Form\Admin\Group\UpdateForm;
use Zentlix\User\Infrastructure\Group\ReadModel\Repository\CycleGroupRepository;

#[GuardNamespace('user_permissions.group')]
final class GroupController extends AbstractRenderController
{
    #[DataGrid(name: 'admin-groups')]
    #[Guarded(permission: 'view')]
    public function groups(CycleGroupRepository $groups): string|Select
    {
        if ($this->input->isAjax()) {
            return $groups->withLocalized();
        }

        return $this->render('user:admin/group/list');
    }

    #[Guarded(permission: 'create')]
    public function create(): string|ResponseInterface
    {
        try {
            $form = $this->formFactory->create(CreateForm::class, new Group());
            $form->handleRequest($this->input);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->exec(new CreateCommand($form->getData()));
                $this->addFlash('success', 'user.group.created_successfully');
                return $this->redirectToRoute('admin.group.update', ['group' => $form->getData()->uuid->toString()]);
            }
        } catch (DomainException $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('admin.group.create');
        }

        return $this->render('user:admin/group/create', ['form' => $form->createView()]);
    }

    #[Guarded(permission: 'update')]
    public function update(GroupView $group): string|ResponseInterface
    {
        try {
            $form = $this->formFactory->create(UpdateForm::class, Group::fromView($group));
            $form->handleRequest($this->input);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->exec(new UpdateCommand($form->getData()));
                $this->addFlash('success', 'user.group.updated_successfully');
                return $this->redirectToRoute('admin.group.list');
            }
        } catch (DomainException $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('admin.group.update', ['group' => $group->uuid->toString()]);
        }

        return $this->render('user:admin/group/update', [
            'form' => $form->createView(),
            'title' => $group->title?->title,
            'uuid'  => $group->uuid->toString()
        ]);
    }

    #[Guarded(permission: 'delete')]
    public function delete(GroupView $group): ResponseInterface
    {
        try {
            $this->exec(new DeleteCommand($group));
            $this->addFlash('success', 'user.group.deleted_successfully');
            return $this->json(['action'  => ['redirect' => (string) $this->router->uri('admin.group.list')]]);
        } catch (DomainException $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->json(['action'  => 'reload']);
        }
    }
}
