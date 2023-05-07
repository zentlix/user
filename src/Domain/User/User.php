<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\User;

use Broadway\EventSourcing\EventSourcedAggregateRoot;
use libphonenumber\PhoneNumber;
use Ramsey\Uuid\UuidInterface;
use Zentlix\User\Domain\User\DataTransferObject\User as UserDTO;
use Zentlix\User\Domain\User\Event\UserSignedIn;
use Zentlix\User\Domain\User\Event\UserWasCreated;
use Zentlix\User\Domain\User\Service\UserValidatorInterface;
use Zentlix\User\Domain\User\ValueObject\Email;

final class User extends EventSourcedAggregateRoot
{
    private UuidInterface $uuid;

    private Email $email;

    private ?PhoneNumber $phone = null;

    /**
     * @var non-empty-string|null
     */
    private ?string $firstName = null;

    /**
     * @var non-empty-string|null
     */
    private ?string $lastName = null;

    /**
     * @var non-empty-string|null
     */
    private ?string $middleName = null;

    private bool $emailConfirmed = false;

    /**
     * @var non-empty-string|null
     */
    private ?string $emailConfirmToken = null;

    /**
     * @var UuidInterface[]
     */
    private array $groups;

    private Status $status;

    /**
     * @var non-empty-string
     */
    private string $password;

    private ResetToken $resetToken;

    private ?Email $newEmail = null;

    /**
     * @var non-empty-string
     */
    private ?string $newEmailToken = null;

    private ?UuidInterface $locale = null;

    private ?\DateTimeImmutable $lastLogin = null;

    private \DateTimeImmutable $updatedAt;

    private \DateTimeImmutable $createdAt;

    public static function create(UserDTO $data, UserValidatorInterface $validator): self
    {
        $validator->preCreate($data);

        $user = new self();
        $user->apply(new UserWasCreated($data));

        return $user;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPhone(): ?PhoneNumber
    {
        return $this->phone;
    }

    /**
     * @return non-empty-string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @return non-empty-string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @return non-empty-string|null
     */
    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getLocale(): ?UuidInterface
    {
        return $this->locale;
    }

    /**
     * @return non-empty-string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return UuidInterface[]
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getLastLogin(): ?\DateTimeImmutable
    {
        return $this->lastLogin;
    }

    public function confirmEmail(): self
    {
        $this->emailConfirmed = true;
        $this->emailConfirmToken = null;

        return $this;
    }

    public function isEmailConfirmed(): bool
    {
        return $this->emailConfirmed;
    }

    public function isBlocked(): bool
    {
        return Status::Blocked === $this->status;
    }

    public function isActive(): bool
    {
        return Status::Active === $this->status;
    }

    public function isWait(): bool
    {
        return Status::Wait === $this->status;
    }

    public function signIn(): void
    {
        $this->apply(new UserSignedIn($this->uuid, $this->email, new \DateTimeImmutable()));
    }

    public function getAggregateRootId(): string
    {
        return $this->uuid->toString();
    }

    protected function applyUserWasCreated(UserWasCreated $event): void
    {
        $this->uuid = $event->data->uuid;
        $this->email = $event->data->getEmail();
        $this->firstName = $event->data->firstName;
        $this->lastName = $event->data->lastName;
        $this->middleName = $event->data->middleName;
        $this->groups = $event->data->getGroups();
        $this->status = $event->data->status;
        $this->password = $event->data->password;
        $this->locale = $event->data->getLocale();
        $this->createdAt = $event->data->createdAt;
        $this->updatedAt = $event->data->updatedAt;
        $this->emailConfirmed = $event->data->emailConfirmed;
        $this->emailConfirmToken = $event->data->emailConfirmToken;
    }

    protected function applyUserSignedIn(UserSignedIn $event): void
    {
        $this->lastLogin = $event->signedInAt;
    }
}
