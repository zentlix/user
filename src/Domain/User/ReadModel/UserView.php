<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\User\ReadModel;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Cycle\Annotated\Annotation\Relation\Embedded;
use Cycle\Annotated\Annotation\Relation\ManyToMany;
use Cycle\Annotated\Annotation\Table\Index;
use Cycle\ORM\Collection\Pivoted\PivotedCollection;
use Doctrine\Common\Collections\Collection;
use libphonenumber\PhoneNumber;
use OpenApi\Attributes as OA;
use Ramsey\Uuid\UuidInterface;
use Spiral\AdminPanel\Security\UserInterface;
use Zentlix\User\Domain\Group\DefaultGroups;
use Zentlix\User\Domain\Group\ReadModel\GroupView;
use Zentlix\User\Domain\Locale\ReadModel\LocaleView;
use Zentlix\User\Domain\User\ResetEmail;
use Zentlix\User\Domain\User\ResetPassword;
use Zentlix\User\Domain\User\Status;
use Zentlix\User\Domain\User\ValueObject\Email;
use Zentlix\User\Infrastructure\Shared\ReadModel\Table;
use Zentlix\User\Infrastructure\User\ReadModel\Repository\CycleUserRepository;

#[OA\Schema(
    schema: 'UserView',
    description: 'User record',
    required: ['uuid', 'email', 'email_confirmed', 'status', 'reset_token', 'created_at', 'updated_at'],
    type: 'object',
)]
#[Index(columns: ['email'], unique: true)]
#[Index(columns: ['phone'], unique: true)]
#[Entity(role: 'user', repository: CycleUserRepository::class, table: Table::Users->value)]
class UserView implements UserInterface
{
    #[OA\Property(property: 'uuid', type: 'string', example: '7be33fd4-ff46-11ea-adc1-0242ac120002')]
    #[Column(type: 'uuid', primary: true, typecast: 'uuid')]
    public UuidInterface $uuid;

    /**
     * @var non-empty-string
     */
    #[Column(type: 'string')]
    public string $password;

    #[OA\Property(property: 'email', type: 'string', example: 'email@domain.com')]
    #[Column(type: 'string', typecast: 'email')]
    public Email $email;

    #[OA\Property(property: 'phone', type: 'string')]
    #[Column(type: 'string', nullable: true, typecast: 'phone')]
    public ?PhoneNumber $phone = null;

    #[Column(type: 'string', name: 'first_name', nullable: true)]
    public ?string $firstName = null;

    #[Column(type: 'string', name: 'last_name', nullable: true)]
    public ?string $lastName = null;

    #[Column(type: 'string', name: 'middle_name', nullable: true)]
    public ?string $middleName = null;

    #[Column(type: 'boolean', name: 'email_confirmed', typecast: 'bool')]
    public bool $emailConfirmed = false;

    #[Column(type: 'string', name: 'email_confirm_token', nullable: true)]
    public ?string $emailConfirmToken = null;

    /**
     * @var Collection<int, GroupView>
     */
    #[ManyToMany(
        target: GroupView::class,
        through: UserGroupView::class
    )]
    public Collection $groups;

    #[OA\Property(
        property: 'status',
        type: 'string',
        enum: ['active', 'blocked', 'waiting']
    )]
    #[Column(type: 'enum(active,blocked,waiting)', name: 'status', typecast: [Status::class, 'typecast'])]
    public Status $status;

    #[Embedded(target: ResetPassword::class, prefix: 'reset_password_')]
    public ResetPassword $resetPassword;

    #[Embedded(target: ResetEmail::class, prefix: 'reset_email_')]
    public ResetEmail $resetEmail;

    #[BelongsTo(target: LocaleView::class, innerKey: 'locale_uuid', outerKey: 'uuid', nullable: true)]
    public ?LocaleView $locale = null;

    #[Column(type: 'datetime', name: 'last_login', nullable: true)]
    public ?\DateTimeImmutable $lastLogin = null;

    #[Column(type: 'datetime', name: 'updated_at')]
    public \DateTimeImmutable $updatedAt;

    #[Column(type: 'datetime', name: 'created_at')]
    public \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->groups = new PivotedCollection();
        $this->resetPassword = new ResetPassword();
        $this->resetEmail = new ResetEmail();
    }

    public function getId(): string
    {
        return $this->uuid->toString();
    }

    /**
     * @return non-empty-string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        $roles = [];
        /** @var GroupView $group */
        foreach ($this->groups as $group) {
            $roles[] = $group->code;
        }

        // guarantee every user at least has ROLE_USER
        $roles[] = DefaultGroups::Users->value;

        return \array_unique($roles);
    }

    public function isAdmin(): bool
    {
        return \in_array(DefaultGroups::Administrators->value, $this->getRoles(), true);
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->uuid->toString();
    }
}
