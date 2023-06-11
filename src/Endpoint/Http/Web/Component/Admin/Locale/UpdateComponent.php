<?php

declare(strict_types=1);

namespace Zentlix\User\Endpoint\Http\Web\Component\Admin\Locale;

use Spiral\AdminPanel\Resource\UpdateResource;
use Spiral\AdminPanel\Session\Flash\FlashBagInterface;
use Spiral\Cqrs\CommandBusInterface;
use Spiral\Livewire\Attribute\Component;
use Spiral\Livewire\Attribute\Model;
use Spiral\Symfony\Form\Component\FormComponent;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Zentlix\Core\Domain\Shared\Exception\DomainException;
use Zentlix\User\Application\Locale\Command\UpdateCommand;
use Zentlix\User\Domain\Locale\DataTransferObject\Locale;
use Zentlix\User\Domain\Locale\ReadModel\LocaleView;
use Zentlix\User\Endpoint\Http\Web\Form\Admin\Locale\UpdateForm;

#[Component(template: 'admin:component/form/tabs')]
final class UpdateComponent extends FormComponent
{
    #[Model]
    public LocaleView $locale;

    #[Model]
    public UpdateResource $resource;

    public function __construct(
        private readonly FormFactoryInterface $formFactory,
        private readonly CommandBusInterface $commandBus,
        private readonly FlashBagInterface $flashBag
    ) {
    }

    public function mount(LocaleView $locale, UpdateResource $updateResource): void
    {
        $this->locale = $locale;
        $this->resource = $updateResource;
    }

    public function createForm(): FormInterface
    {
        return $this->formFactory->create(UpdateForm::class, Locale::fromLocale($this->locale));
    }

    public function submit(): void
    {
        /** @var Locale $data */
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
