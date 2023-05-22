<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\User\ValueObject;

final class Email implements \Stringable
{
    /**
     * @var non-empty-string
     */
    private string $value;

    /**
     * @return non-empty-string
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * @return non-empty-string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    public function isEqual(self $other): bool
    {
        return $this->getValue() === $other->getValue();
    }

    public static function fromString(string $email): self
    {
        if (empty($email) || !\filter_var($email, \FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Incorrect Email.');
        }

        $self = new self();
        $self->value = \mb_strtolower($email);

        return $self;
    }
}
