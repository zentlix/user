<?php

declare(strict_types=1);

namespace Zentlix\User\Endpoint\Http\Web\Form\Admin;

use Spiral\Filament\Form\AbstractType;
use Spiral\Filament\Form\Type\CheckboxType;
use Spiral\Filament\Form\Type\EmailType;
use Spiral\Filament\Form\Type\PasswordType;
use Spiral\Filament\Form\Type\SubmitType;
use Spiral\Symfony\Form\Attribute\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;

#[FormType]
final class SignInForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, ['label' => 'Email address', 'constraints' => [new Email()]])
            ->add('password', PasswordType::class, ['label' => 'Password'])
            ->add('remember_me', CheckboxType::class, ['label' => 'Remember me', 'required' => false])
            ->add('save', SubmitType::class, ['label' => 'Sign in'])
        ;
    }
}
