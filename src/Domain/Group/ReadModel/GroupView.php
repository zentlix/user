<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Group\ReadModel;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\HasMany;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use OpenApi\Attributes as OA;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Ignore;
use Zentlix\User\Domain\Group\Exception\TitleNotFoundException;
use Zentlix\User\Domain\Group\Role;
use Zentlix\User\Infrastructure\Group\ReadModel\Repository\CycleGroupRepository;

#[OA\Schema(
    schema: 'GroupView',
    description: 'User group record',
    required: ['uuid', 'titles', 'code', 'sort'],
    type: 'object',
)]
#[Entity(role: 'group', repository: CycleGroupRepository::class, table: 'zx_groups')]
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
     * @var non-empty-string
     */
    #[Column(type: 'string')]
    public string $code;

    /**
     * @var positive-int
     */
    #[Column(type: 'integer')]
    public int $sort;

    #[Ignore]
    #[Column(type: 'string', typecast: [Role::class, 'typecast'])]
    public Role $role;

    /**
     * @var string[]
     */
    #[Ignore]
    #[Column(type: 'json', typecast: 'json')]
    public array $rights = [];

    public function __construct()
    {
        $this->titles = new ArrayCollection();
    }

    #[Ignore]
    public function getId(): string
    {
        return $this->uuid->toString();
    }

    /**
     * @return non-empty-string
     *
     * @throws TitleNotFoundException
     */
    public function getTitle(UuidInterface $locale, ?UuidInterface $fallbackLocale = null): string
    {
        foreach ($this->titles as $lang) {
            if ($lang->locale->equals($locale)) {
                return $lang->title;
            }
        }

        if ($fallbackLocale !== null) {
            foreach ($this->titles as $lang) {
                if ($lang->locale->equals($fallbackLocale)) {
                    return $lang->title;
                }
            }
        }

        throw new TitleNotFoundException($locale->toString());
    }
}
