<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\User\DataTransferObject;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberUtil;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Spiral\PhoneNumber\Validator\Constraints\PhoneNumber as PhoneNumberConstraint;
use Symfony\Component\Validator\Constraints;
use Zentlix\User\Domain\Group\ReadModel\GroupView;
use Zentlix\User\Domain\User\ReadModel\UserView;
use Zentlix\User\Domain\User\Status;
use Zentlix\User\Domain\User\ValueObject\Email;

final class User
{
    #[Constraints\Uuid]
    public UuidInterface $uuid;

    #[Constraints\NotBlank]
    #[Constraints\Email]
    private Email $email;

    #[PhoneNumberConstraint]
    private ?PhoneNumber $phone = null;

    /**
     * @var non-empty-string|null
     */
    #[Constraints\Type('string')]
    public ?string $firstName = null;

    /**
     * @var non-empty-string|null
     */
    #[Constraints\Type('string')]
    public ?string $lastName = null;

    /**
     * @var non-empty-string|null
     */
    #[Constraints\Type('string')]
    public ?string $middleName = null;


    #[Constraints\Type('array')]
    public array $groups;

    #[Constraints\NotBlank]
    public Status $status = Status::Active;

    private ?UuidInterface $locale = null;

    /**
     * @var non-empty-string|null
     */
    public ?string $password = null;

    public \DateTimeImmutable $createdAt;

    public \DateTimeImmutable $updatedAt;

    public ?\DateTimeImmutable $lastLogin = null;

    #[Constraints\Type('bool')]
    public bool $emailConfirmed;

    /**
     * @var non-empty-string|null
     */
    #[Constraints\Type('string')]
    public ?string $emailConfirmToken = null;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
        $this->emailConfirmToken = Uuid::uuid4()->toString();
        $this->emailConfirmed = false;
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

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

    public function getPhone(): ?PhoneNumber
    {
        return $this->phone;
    }

    /**
     * @param non-empty-string|PhoneNumber|null $phone
     *
     * @throws NumberParseException
     */
    public function setPhone(string|PhoneNumber|null $phone): self
    {
        if (null === $phone) {
            return $this;
        }

        $this->phone = $phone instanceof PhoneNumber
            ? $phone
            : PhoneNumberUtil::getInstance()->parse($phone, PhoneNumberUtil::UNKNOWN_REGION);

        return $this;
    }

    public function getLocale(): ?UuidInterface
    {
        return $this->locale;
    }

    /**
     * @param non-empty-string|UuidInterface|null $locale
     */
    public function setLocale(string|UuidInterface|null $locale = null): self
    {
        if (null !== $locale) {
            $this->locale = $locale instanceof UuidInterface ? $locale : Uuid::fromString($locale);
        }

        return $this;
    }

    public function setGroups(array $groups): self
    {
        $this->groups = \array_map(
            static fn (string|UuidInterface $group) => \is_string($group) ? Uuid::fromString($group) : $group,
            $groups
        );

        return $this;
    }


    public function addGroup(string|UuidInterface $group): self
    {
        $this->groups[] = $group instanceof UuidInterface ? $group : Uuid::fromString($group);

        return $this;
    }

    /**
     * @return UuidInterface[]
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    public static function fromView(UserView $user): self
    {
        $self = new self();
        $self->uuid = $user->uuid;
        $self->email = $user->email;
        $self->phone = $user->phone;
        $self->firstName = $user->firstName;
        $self->lastName = $user->lastName;
        $self->middleName = $user->middleName;
        $self->groups = \array_map(static fn (GroupView $group) => $group->uuid, $user->groups->toArray());
        $self->status = $user->status;
        $self->locale = $user->locale?->uuid;
        $self->password = $user->password;
        $self->createdAt = $user->createdAt;
        $self->updatedAt = $user->updatedAt;
        $self->lastLogin = $user->lastLogin;
        $self->emailConfirmed = $user->emailConfirmed;
        $self->emailConfirmToken = $user->emailConfirmToken;

        return $self;
    }
}
