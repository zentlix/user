<?php

declare(strict_types=1);

namespace Zentlix\User\Endpoint\Http\Web\Form\Admin\Locale;

use Psr\EventDispatcher\EventDispatcherInterface;
use Spiral\AdminPanel\Form\Type;
use Spiral\Symfony\Form\AbstractType;
use Spiral\Symfony\Form\Attribute\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Zentlix\User\Domain\Locale\DataTransferObject\Locale;
use Zentlix\User\Domain\Locale\Event\UpdateLocaleForm;

#[FormType]
final class UpdateForm extends AbstractType
{
    public function __construct(
        private readonly EventDispatcherInterface $dispatcher
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            $builder->create('main', Type\FormType::class, ['inherit_data' => true, 'label' => 'user.main'])
                ->add('title', Type\TextType::class, ['label' => 'user.title'])
                ->add('active', Type\CheckboxType::class, [
                    'label' => 'user.locale.language_active',
                    'required' => false
                ])
                ->add('sort', Type\IntegerType::class, ['label' => 'user.sort'])
        );

        $this->dispatcher->dispatch(new UpdateLocaleForm($builder));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Locale::class]);
    }
}
