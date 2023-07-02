<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Group\ReadModel;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\HasMany;
use Cycle\Annotated\Annotation\Relation\HasOne;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use OpenApi\Attributes as OA;
use Ramsey\Uuid\UuidInterface;
use Spiral\Security\RuleInterface;
use Zentlix\User\Infrastructure\Group\ReadModel\Repository\CycleGroupRepository;
use Zentlix\User\Infrastructure\Shared\ReadModel\Table;

#[OA\Schema(
    schema: 'GroupView',
    description: 'Group record',
    required: ['uuid', 'titles', 'code', 'sort'],
    type: 'object',
)]
#[Entity(role: 'group', repository: CycleGroupRepository::class, table: Table::Groups->value)]
class GroupView
{
    #[OA\Property(property: 'uuid', type: 'string', example: '7be33fd4-ff46-11ea-adc1-0242ac120002')]
    #[Column(type: 'uuid', primary: true, typecast: 'uuid')]
    public UuidInterface $uuid;

    /**
     * @var Collection<int, TitleView>
     */
    #[HasMany(target: TitleView::class, innerKey: 'uuid', outerKey: 'group')]
    public Collection $titles;

    /**
     * Localized title.
     */
    #[HasOne(target: TitleView::class, innerKey: 'uuid', outerKey: 'group')]
    public ?TitleView $title = null;

    /**
     * @var non-empty-string
     */
    #[Column(type: 'string')]
    public string $code;

    /**
     * @var positive-int
     */
    #[Column(type: 'integer')]
    public int $sort;

    #[Column(type: 'string')]
    public string $access;

    /**
     * @var array<non-empty-string, class-string<RuleInterface>>
     */
    #[Column(type: 'json', typecast: 'json')]
    public array $permissions = [];

    public function __construct()
    {
        $this->titles = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->uuid->toString();
    }
}
