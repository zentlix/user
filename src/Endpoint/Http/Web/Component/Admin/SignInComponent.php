<?php

declare(strict_types=1);

namespace Zentlix\User\Endpoint\Http\Web\Component\Admin;

use Spiral\Cqrs\CommandBusInterface;
use Spiral\Filament\Config\FilamentConfig;
use Spiral\Filament\Exception\InvalidEmailException;
use Spiral\Filament\Exception\InvalidPasswordException;
use Spiral\Livewire\Attribute\Component;
use Spiral\Session\SessionScope;
use Spiral\Symfony\Form\Component\FormComponent;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Zentlix\User\Application\User\Command\Auth\SignInCommand;
use Zentlix\User\Domain\User\ValueObject\Email;
use Zentlix\User\Endpoint\Http\Web\Form\Admin\SignInForm;

#[Component(name: 'sign-in', template: 'forms:livewire/form')]
final class SignInComponent extends FormComponent
{
    /**
     * @see https://en.wikipedia.org/wiki/ISO_8601#Durations
     */
    private const DEFAULT_DURATION  = 'P1D';
    private const REMEMBER_DURATION = 'P1M';

    public function __construct(
        FormFactoryInterface $formFactory,
        private readonly CommandBusInterface $commandBus,
        private readonly SessionScope $sessionScope,
        private readonly FilamentConfig $config
    ) {
        parent::__construct($formFactory);
    }

    public function createForm(): FormInterface
    {
        return $this->formFactory->create(SignInForm::class);
    }

    public function submit(): void
    {
        /**
         * @var array{
         *     email: non-empty-string,
         *     password: non-empty-string,
         *     remember_me: bool
         * } $data
         */
        $data = $this->form->getData();

        try {
            $this->commandBus->dispatch(new SignInCommand(
                email: Email::fromString($data['email']),
                plainPassword: $data['password'],
                sessionExpiration: $this->getSessionExpiration($data['remember_me'])
            ));

            $targetPath = $this->sessionScope->getActiveSession()->getSection('auth')->get('target_path');
            !empty($targetPath)
                ? $this->redirectTo($targetPath)
                : $this->redirectToRoute($this->config->getDashboardRoute());
        } catch (InvalidEmailException $e) {
            $this->form->get('email')->addError(new FormError($e->getMessage()));
        } catch (InvalidPasswordException $e) {
            $this->form->get('password')->addError(new FormError($e->getMessage()));
        }
    }

    private function getSessionExpiration(bool $rememberMe): \DateTimeInterface
    {
        $now = new \DateTime();

        if ($rememberMe) {
            return $now->add(new \DateInterval(self::REMEMBER_DURATION));
        }

        return $now->add(new \DateInterval(self::DEFAULT_DURATION));
    }
}
