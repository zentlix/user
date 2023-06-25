<?php

declare(strict_types=1);

namespace Zentlix\User\Endpoint\Http\Web\Form\Admin\Group;

use Psr\EventDispatcher\EventDispatcherInterface;
use Spiral\AdminPanel\Form\Type;
use Spiral\Symfony\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Zentlix\User\Domain\Group\DataTransferObject\Group;
use Zentlix\User\Domain\Group\DefaultAccess;
use Zentlix\User\Domain\Translator\TranslatorInterface;

abstract class GroupForm extends AbstractType
{
    public function __construct(
        protected readonly EventDispatcherInterface $eventDispatcher,
        protected readonly TranslatorInterface $translator
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $main = $builder->create('main', Type\FormType::class, ['inherit_data' => true, 'label' => 'user.main']);

        $main
            ->add('titles', Type\CollectionType::class, [
                'entry_type' => GroupTitleForm::class,
                'entry_options' => ['label' => false],
                'row_attr' => ['class' => 'mb-0'],
                'label' => false,
            ])
            ->add('code', Type\TextType::class, ['label' => 'user.symbol_code'])
            ->add('access', Type\ChoiceType::class, [
                'label' => 'user.group.group_access',
                'choices' => [
                    'user.group.admin_role' => DefaultAccess::Admin->value,
                    'user.group.user_role' => DefaultAccess::User->value
                ],
            ])
            ->add('sort', Type\IntegerType::class, ['label' => 'user.sort']);
        $builder->add($main);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Group::class]);
    }
}
