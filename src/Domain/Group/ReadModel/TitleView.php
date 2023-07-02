<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Group\ReadModel;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use OpenApi\Attributes as OA;
use Ramsey\Uuid\UuidInterface;
use Zentlix\User\Domain\Group\DataTransferObject\Title;
use Zentlix\User\Infrastructure\Shared\ReadModel\Table;

#[OA\Schema(
    schema: 'GroupTitleView',
    description: 'User group title record',
    required: ['uuid', 'title', 'group', 'locale'],
    type: 'object',
)]
#[Entity(role: 'group_title', table: Table::GroupTitles->value)]
class TitleView
{
    /**
     * @var non-empty-string
     */
    #[Column(type: 'string')]
    public string $title;

    #[Column(type: 'uuid', name: 'group_uuid', primary: true, typecast: 'uuid')]
    public UuidInterface $group;

    #[Column(type: 'uuid', name: 'locale_uuid', primary: true, typecast: 'uuid')]
    public UuidInterface $locale;

    public function __construct(Title $title)
    {
        $this->title = $title->title;
        $this->group = $title->getGroup();
        $this->locale = $title->getLocale();
    }
}
