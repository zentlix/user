<?php

declare(strict_types=1);

namespace Zentlix\User\Application\User\Command\Auth;

use Zentlix\Core\Application\Shared\Command\CommandInterface;
use Zentlix\User\Domain\User\ValueObject\Email;

final class SignInCommand implements CommandInterface
{
    /**
     * @see https://en.wikipedia.org/wiki/ISO_8601#Durations
     */
    private const DEFAULT_DURATION  = 'P1D';
    private const REMEMBER_DURATION = 'P1M';

    private Email $email;

    public string $password;
    public bool $remember_me;

    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @param Email|non-empty-string $email
     */
    public function setEmail(Email|string $email): self
    {
        $this->email = $email instanceof Email ? $email : Email::fromString($email);

        return $this;
    }

    public function getSessionExpiration(): \DateTimeInterface
    {
        $now = new \DateTime();

        if ($this->remember_me) {
            return $now->add(new \DateInterval(self::REMEMBER_DURATION));
        }

        return $now->add(new \DateInterval(self::DEFAULT_DURATION));
    }
}
