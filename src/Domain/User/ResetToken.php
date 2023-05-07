<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\User;

final class ResetToken
{
    /**
     * @var non-empty-string|null
     */
    private ?string $token;

    private ?\DateTimeImmutable $expires;

    /**
     * @param non-empty-string|null $token
     */
    public function __construct(string $token = null, \DateTimeImmutable $expires = null)
    {
        $this->token = $token;
        $this->expires = $expires;
    }

    public function isExpiredTo(\DateTimeImmutable $expiredTo): bool
    {
        return $this->expires <= $expiredTo;
    }

    /**
     * @return non-empty-string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    public function getExpires(): ?\DateTimeImmutable
    {
        return $this->expires;
    }
}
