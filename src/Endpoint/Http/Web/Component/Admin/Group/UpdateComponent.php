<?php

declare(strict_types=1);

namespace Zentlix\User\Endpoint\Http\Web\Component\Admin\Group;

use Spiral\AdminPanel\Resource\UpdateResource;
use Spiral\AdminPanel\Session\Flash\FlashBagInterface;
use Spiral\Cqrs\CommandBusInterface;
use Spiral\Livewire\Attribute\Component;
use Spiral\Livewire\Attribute\Model;
use Spiral\Symfony\Form\Component\FormComponent;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Zentlix\Core\Domain\Shared\Exception\DomainException;
use Zentlix\User\Application\Group\Command\UpdateCommand;
use Zentlix\User\Domain\Group\DataTransferObject\Group;
use Zentlix\User\Domain\Group\ReadModel\GroupView;
use Zentlix\User\Endpoint\Http\Web\Form\Admin\Group\UpdateForm;

#[Component(template: 'admin:component/form/tabs')]
final class UpdateComponent extends FormComponent
{
    #[Model]
    public GroupView $group;

    #[Model]
    public UpdateResource $resource;

    public function __construct(
        private readonly FormFactoryInterface $formFactory,
        private readonly CommandBusInterface $commandBus,
        private readonly FlashBagInterface $flashBag
    ) {
    }

    public function mount(GroupView $group, UpdateResource $updateResource): void
    {
        $this->group = $group;
        $this->resource = $updateResource;
    }

    public function createForm(): FormInterface
    {
        return $this->formFactory->create(UpdateForm::class, Group::fromGroup($this->group));
    }

    public function submit(): void
    {
        /** @var Group $data */
        $data = $this->form->getData();

        try {
            $this->commandBus->dispatch(new UpdateCommand($data));

            $this->flashBag->set('success', $this->resource->successMessage);
            $this->redirectTo($this->resource->redirectTo);
        } catch (DomainException $e) {
            $this->flashBag->set('error', $e->getMessage());
        }
    }
}
