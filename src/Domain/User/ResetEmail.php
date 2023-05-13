<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\User;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Embeddable;

#[Embeddable]
class ResetEmail
{
    /**
     * @var non-empty-string|null
     */
    #[Column(type: 'string', nullable: true)]
    private ?string $token = null;

    #[Column(type: 'datetime', nullable: true)]
    private ?\DateTimeImmutable $expires = null;

    /**
     * @var non-empty-string|null
     */
    #[Column(type: 'string', nullable: true)]
    private ?string $newEmail = null;

    /**
     * @param non-empty-string|null $token
     */
    public function __construct(?string $newEmail = null, ?string $token = null, ?\DateTimeImmutable $expires = null)
    {
        $this->token = $token;
        $this->expires = $expires;
    }

    public function isExpiredTo(\DateTimeImmutable $expiredTo = new \DateTimeImmutable()): bool
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
