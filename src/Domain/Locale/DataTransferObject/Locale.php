<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Locale\DataTransferObject;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints;

class Locale
{
    public UuidInterface $uuid;

    /**
     * @var non-empty-string
     */
    #[Constraints\NotBlank]
    #[Constraints\Type('string')]
    public string $title;

    /**
     * @var positive-int
     */
    #[Constraints\Positive]
    #[Constraints\Type('int')]
    public int $sort = 1;

    #[Constraints\Type('bool')]
    public bool $active = true;

    /**
     * @var non-empty-string
     */
    #[Constraints\NotBlank]
    #[Constraints\Type('string')]
    private string $code;

    /**
     * @var non-empty-string
     */
    #[Constraints\NotBlank]
    #[Constraints\Type('string')]
    #[SerializedName('country_code')]
    private string $countryCode;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
    }

    /**
     * @return non-empty-string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param non-empty-string $code
     */
    public function setCode(string $code): self
    {
        $this->code = \strtolower($code);

        return $this;
    }

    /**
     * @return non-empty-string
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    /**
     * @param non-empty-string $countryCode
     */
    public function setCountryCode(string $countryCode): self
    {
        $this->countryCode = \strtoupper($countryCode);

        return $this;
    }
}
