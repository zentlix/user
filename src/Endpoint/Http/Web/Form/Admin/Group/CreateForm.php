<?php

declare(strict_types=1);

namespace Zentlix\User\Endpoint\Http\Web\Form\Admin\Group;

use Psr\EventDispatcher\EventDispatcherInterface;
use Spiral\Symfony\Form\Attribute\FormType;
use Spiral\Translator\TranslatorInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Zentlix\User\Domain\Group\DataTransferObject\Group;
use Zentlix\User\Domain\Group\Event\CreateGroupForm;
use Zentlix\User\Domain\Locale\ReadModel\Repository\LocaleRepositoryInterface;

#[FormType]
final class CreateForm extends GroupForm
{
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        TranslatorInterface $translator,
        private readonly LocaleRepositoryInterface $localeRepository
    ) {
        parent::__construct($eventDispatcher, $translator);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $group = new Group();
        foreach ($this->localeRepository->findActive() as $locale) {
            $group->setTitle('', $locale->uuid);
        }
        $builder->setData($group);

        parent::buildForm($builder, $options);

        $this->eventDispatcher->dispatch(new CreateGroupForm($builder));
    }
}
