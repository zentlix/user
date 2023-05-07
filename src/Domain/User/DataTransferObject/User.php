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

    /**
     * @var UuidInterface[]
     */
    #[Constraints\Type('array')]
    protected array $groups;

    #[Constraints\NotBlank]
    public Status $status = Status::Active;

    private ?UuidInterface $locale = null;

    /**
     * @var non-empty-string
     */
    #[Constraints\NotBlank]
    #[Constraints\Length(min: 6)]
    #[Constraints\Type('string')]
    public string $password;

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

        $this->phone = $phone instanceof PhoneNumber ? $phone : PhoneNumberUtil::getInstance()->parse($phone);

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

    /**
     * @param array<UuidInterface|non-empty-string> $groups
     */
    public function setGroups(array $groups): self
    {
        $this->groups = \array_map(
            static fn (string|UuidInterface $group) => \is_string($group) ? Uuid::fromString($group) : $group,
            $groups
        );

        return $this;
    }

    /**
     * @param non-empty-string|UuidInterface $group
     */
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
}
