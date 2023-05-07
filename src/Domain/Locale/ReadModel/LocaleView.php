<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Locale\ReadModel;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use OpenApi\Attributes as OA;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Zentlix\User\Infrastructure\Locale\ReadModel\Repository\CycleLocaleRepository;

#[OA\Schema(
    schema: 'LocaleView',
    description: 'Locale record',
    required: ['uuid', 'title', 'code', 'country_code', 'active', 'sort'],
    type: 'object',
)]
#[Entity(role: 'locale', repository: CycleLocaleRepository::class, table: 'zx_locales')]
class LocaleView
{
    #[OA\Property(type: 'string', example: '7be33fd4-ff46-11ea-adc1-0242ac120002')]
    #[Column(type: 'uuid', primary: true, typecast: 'uuid')]
    public UuidInterface $uuid;

    /**
     * @var non-empty-string
     */
    #[OA\Property(type: 'string', example: 'English')]
    #[Column(type: 'string')]
    public string $title;

    /**
     * @var non-empty-string
     */
    #[OA\Property(type: 'string', example: 'en')]
    #[Column(type: 'string')]
    public string $code;

    /**
     * @var non-empty-string
     */
    #[OA\Property(property: 'country_code', type: 'string', example: 'US')]
    #[Column(type: 'string', name: 'country_code')]
    #[SerializedName('country_code')]
    public string $countryCode;

    #[OA\Property(type: 'boolean')]
    #[Column(type: 'boolean')]
    public bool $active;

    /**
     * @var positive-int
     */
    #[OA\Property(type: 'integer', example: '1')]
    #[Column(type: 'integer')]
    public int $sort;

    public function getId(): string
    {
        return $this->uuid->toString();
    }

    /**
     * Returns language and country code, such as fr_FR.
     *
     * @return non-empty-string
     */
    public function getFullCode(): string
    {
        return $this->code.'_'.$this->countryCode;
    }
}
