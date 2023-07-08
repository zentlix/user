<?php

declare(strict_types=1);

namespace Zentlix\User\Endpoint\Http\Web\Controller\Admin;

use Psr\Http\Message\ResponseInterface;
use Spiral\AdminPanel\Config\AdminPanelConfig;
use Spiral\AdminPanel\Exception\InvalidEmailException;
use Spiral\AdminPanel\Exception\InvalidPasswordException;
use Symfony\Component\Form\FormError;
use Zentlix\Core\Endpoint\Http\Web\Controller\Admin\AbstractAdminController;
use Zentlix\User\Endpoint\Http\Web\Form\Admin\Auth\SignInForm;

final class AuthController extends AbstractAdminController
{
    public function login(AdminPanelConfig $config): string|ResponseInterface
    {
        $form = $this->formFactory->create(SignInForm::class);

        try {
            $form->handleRequest($this->input);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->exec($form->getData());
                return $this->redirectToRoute($config->getDashboardRoute());
            }
        } catch (InvalidEmailException $e) {
            $form->get('email')->addError(new FormError($e->getMessage()));
        } catch (InvalidPasswordException $e) {
            $form->get('password')->addError(new FormError($e->getMessage()));
        }

        return $this->render('user:admin/security/sign-in', [
            'title' => $this->translator->trans('Login'),
            'form'  => $form->createView()
        ]);
    }
}
