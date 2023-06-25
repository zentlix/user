<?php

declare(strict_types=1);

namespace Zentlix\User\Endpoint\Http\Web\Form\Admin\Group;

use Psr\EventDispatcher\EventDispatcherInterface;
use Spiral\AdminPanel\Form\Type;
use Spiral\Security\PermissionsInterface;
use Spiral\Symfony\Form\Attribute\FormType;
use Spiral\Translator\TranslatorInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Zentlix\User\Domain\Group\DataTransferObject\Group;
use Zentlix\User\Domain\Group\DefaultAccess;
use Zentlix\User\Domain\Group\DefaultGroups;
use Zentlix\User\Domain\Group\Event\UpdateGroupForm;

#[FormType]
final class UpdateForm extends GroupForm
{
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        TranslatorInterface $translator,
        private readonly PermissionsInterface $permissions
    ) {
        parent::__construct($eventDispatcher, $translator);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        /** @var Group $group */
        $group = $builder->getData();

        $isDefault = \in_array(
            $group->code,
            array_map(static fn (DefaultGroups $group): string => $group->value, DefaultGroups::cases()),
            true
        );

        $code = $builder->get('main')->get('code')->getOptions();
        $role = $builder->get('main')->get('access')->getOptions();

        $builder->get('main')->add('code', Type\TextType::class, array_replace($code, [
            'required' => true,
            'disabled' => $isDefault
        ]));
        $builder->get('main')->add('access', Type\ChoiceType::class, array_replace($role, [
            'disabled' => $isDefault
        ]));

        if ($group->access === DefaultAccess::Admin->value) {
            $permissions = $builder
                ->create('permissions', Type\FormType::class, ['inherit_data' => true, 'label' => 'user.permissions']);

            $permissions->add('permissions', Type\CollectionType::class, [
                'entry_type' => PermissionType::class,
                'entry_options' => [
                    'disabled' => $group->code === DefaultGroups::Administrators->value,
                    'required' => false,
                ],
                'row_attr' => ['class' => 'mb-0'],
                'label' => false,
            ]);

            $builder->add($permissions);
        }

        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
        $builder->addEventListener(FormEvents::POST_SUBMIT, [$this, 'onPostSubmit']);

        $this->eventDispatcher->dispatch(new UpdateGroupForm($builder));
    }

    public function onPreSetData(FormEvent $event): void
    {
        /** @var Group $group */
        $group = $event->getData();
        $group->permissions = Permissions::transformToFormData(
            $group->permissions + $this->permissions->getPermissions($group->code)
        );
    }

    public function onPostSubmit(FormEvent $event): void
    {
        /** @var Group $group */
        $group = $event->getData();
        $group->permissions = Permissions::transformToNormalizedData($group->permissions);
    }
}
