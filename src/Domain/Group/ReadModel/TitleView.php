<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Group\ReadModel;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Table\Index;
use OpenApi\Attributes as OA;
use Ramsey\Uuid\UuidInterface;

#[OA\Schema(
    schema: 'GroupTitleView',
    description: 'User group title record',
    required: ['uuid', 'title', 'group', 'locale'],
    type: 'object',
)]
#[Entity(role: 'group_title', table: 'zx_group_titles')]
class TitleView
{
    /**
     * @var non-empty-string $title
     */
    #[Column(type: 'string')]
    public string $title;

    #[Column(type: 'uuid', name: 'group_id', primary: true, typecast: 'uuid')]
    public UuidInterface $group;

    #[Column(type: 'uuid', name: 'locale_id', primary: true, typecast: 'uuid')]
    public UuidInterface $locale;
}
