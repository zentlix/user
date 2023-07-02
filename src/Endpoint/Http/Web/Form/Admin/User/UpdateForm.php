<?php

declare(strict_types=1);

namespace Zentlix\User\Endpoint\Http\Web\Form\Admin\User;

use Spiral\AdminPanel\Form\Type\RepeatedType;
use Spiral\Symfony\Form\Attribute\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Zentlix\User\Domain\User\Event\UpdateUserForm;

#[FormType]
final class UpdateForm extends UserForm
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $password = $builder->get('main')->get('password')->getOptions();

        $builder->get('main')->add('password', RepeatedType::class, \array_replace($password, [
            'required' => false,
        ]));

        $this->eventDispatcher->dispatch(new UpdateUserForm($builder));
    }
}
