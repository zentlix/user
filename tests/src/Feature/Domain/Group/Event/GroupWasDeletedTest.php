<?php

declare(strict_types=1);

namespace Tests\User\Feature\Domain\Group\Event;

use Ramsey\Uuid\Uuid;
use Spiral\Marshaller\MarshallerInterface;
use Tests\User\Feature\TestCase;
use Zentlix\User\Domain\Group\Event\GroupWasDeleted;

final class GroupWasDeletedTest extends TestCase
{
    public function testMarshall(): void
    {
        $marshaller = $this->getContainer()->get(MarshallerInterface::class);

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
        $marshaller = $this->getContainer()->get(MarshallerInterface::class);

        $this->assertEquals($event, $marshaller->unmarshal([
            'uuid' => '00000000-0000-0000-0000-000000000000',
            'deleted_at' => '2023-06-30T10:51:26+00:00',
        ], (new \ReflectionClass(GroupWasDeleted::class))->newInstanceWithoutConstructor()));
    }
}
