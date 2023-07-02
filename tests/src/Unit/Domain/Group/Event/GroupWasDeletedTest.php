<?php

declare(strict_types=1);

namespace src\Unit\Domain\Group\Event;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Spiral\Attributes\AttributeReader;
use Spiral\Marshaller\Mapper\AttributeMapperFactory;
use Spiral\Marshaller\Marshaller;
use Spiral\Security\Rule\AllowRule;
use Spiral\Security\Rule\ForbidRule;
use Zentlix\User\Domain\Group\DataTransferObject\Group;
use Zentlix\User\Domain\Group\Event\GroupWasCreated;
use Zentlix\User\Domain\Group\Event\GroupWasDeleted;

final class GroupWasDeletedTest extends TestCase
{
    public function testMarshall(): void
    {
        $marshaller = new Marshaller(new AttributeMapperFactory(new AttributeReader()));

        $this->assertEquals(
            [
                'uuid' => '00000000-0000-0000-0000-000000000000',
                'deleted_at' => '2023-06-30T10:51:26+00:00',
            ],
            $marshaller->marshal(new GroupWasDeleted(
                Uuid::fromString('00000000-0000-0000-0000-000000000000'),
                \DateTimeImmutable::createFromFormat(\DateTimeInterface::RFC3339, '2023-06-30T10:51:26+00:00')
            ))
        );
    }

    public function testUnmarshal(): void
    {
        $event = new GroupWasDeleted(
            Uuid::fromString('00000000-0000-0000-0000-000000000000'),
            \DateTimeImmutable::createFromFormat(\DateTimeInterface::RFC3339, '2023-06-30T10:51:26+00:00')
        );
        $marshaller = new Marshaller(new AttributeMapperFactory(new AttributeReader()));

        $this->assertEquals($event, $marshaller->unmarshal([
            'uuid' => '00000000-0000-0000-0000-000000000000',
            'deleted_at' => '2023-06-30T10:51:26+00:00',
        ], (new \ReflectionClass(GroupWasDeleted::class))->newInstanceWithoutConstructor()));
    }
}
