<?php

declare(strict_types=1);

namespace Zentlix\User\Endpoint\Http\Web\Form\Admin\User;

use Psr\EventDispatcher\EventDispatcherInterface;
use Spiral\AdminPanel\Form\Type;
use Spiral\PhoneNumber\Form\Type\PhoneNumberType;
use Spiral\Symfony\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Zentlix\User\Domain\Group\ReadModel\Repository\GroupRepositoryInterface;
use Zentlix\User\Domain\Locale\ReadModel\Repository\LocaleRepositoryInterface;
use Zentlix\User\Domain\Translator\TranslatorInterface;
use Zentlix\User\Domain\User\DataTransferObject\User;
use Zentlix\User\Domain\User\Status;

abstract class UserForm extends AbstractType
{
    public function __construct(
        protected readonly EventDispatcherInterface $eventDispatcher,
        protected readonly GroupRepositoryInterface $groupRepository,
        protected readonly LocaleRepositoryInterface $localeRepository,
        protected readonly TranslatorInterface $translator
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $groups = [];
        foreach ($this->groupRepository->findAll() as $group) {
            $groups[$group->title?->title] = $group->uuid->toString();
        }

        $locales = [];
        foreach ($this->localeRepository->findActive() as $locale) {
            $locales[$locale->title] = $locale->uuid->toString();
        }

        $main = $builder->create('main', Type\FormType::class, ['inherit_data' => true, 'label' => 'user.main']);

        $main
            ->add('email', Type\EmailType::class, ['label' => 'user.email'])
            ->add('password', Type\RepeatedType::class, [
                'type' => Type\PasswordType::class,
                'invalid_message' => $this->translator->trans('user.validation.password_equal'),
                'first_options' => ['label' => 'user.password'],
                'second_options' => ['label' => 'user.password_confirm'],
                'required' => true,
                'error_mapping' => [
                    '.' => 'second'
                ]
            ])
            ->add('phone', PhoneNumberType::class, ['label' => 'user.phone', 'required' => false])
            ->add('firstName', Type\TextType::class, [
                'label' => 'user.user.first_name',
                'required' => false
            ])
            ->add('lastName', Type\TextType::class, [
                'label' => 'user.user.last_name',
                'required' => false
            ])
            ->add('middleName', Type\TextType::class, [
                'label' => 'user.user.middle_name',
                'required' => false
            ])
            ->add('emailConfirmed', Type\CheckboxType::class, [
                'label' => 'user.user.email_confirmed',
                'required' => false
            ])
            ->add('groups', Type\ChoiceType::class, [
                'label' => 'user.user.groups',
                'multiple' => true,
                'choices' => $groups,
            ])
            ->add('status', Type\EnumType::class, [
                'label' => 'user.user.status',
                'class' => Status::class,
                'choice_label' => fn (Status $choice): string => match ($choice) {
                    Status::Active => 'user.user.active',
                    Status::Blocked => 'user.user.blocked',
                    Status::Waiting => 'user.user.waiting',
                },
            ])
            ->add('locale', Type\ChoiceType::class, [
                'label' => 'user.user.locale',
                'choices' => $locales,
                'required' => false,
                'placeholder' => 'user.not_selected'
            ]);
        $builder->add($main);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => User::class]);
    }
}
