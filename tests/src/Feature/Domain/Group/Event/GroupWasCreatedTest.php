<?php

declare(strict_types=1);

namespace Tests\User\Feature\Domain\Group\Event;

use Ramsey\Uuid\Uuid;
use Spiral\Marshaller\MarshallerInterface;
use Spiral\Security\Rule\AllowRule;
use Spiral\Security\Rule\ForbidRule;
use Tests\User\Feature\TestCase;
use Zentlix\User\Domain\Group\DataTransferObject\Group;
use Zentlix\User\Domain\Group\Event\GroupWasCreated;

final class GroupWasCreatedTest extends TestCase
{
    public function testMarshall(): void
    {
        $group = new Group();
        $group->uuid = Uuid::fromString('00000000-0000-0000-0000-000000000000');
        $group->code = 'simple-code';
        $group->sort = 1;
        $group->access = 'admin';
        $group->permissions = ['foo' => AllowRule::class, 'bar' => ForbidRule::class, 'baz' => true];
        $group->setTitle('Some title', Uuid::fromString('00000000-0000-0000-0000-000000000001'));

        $marshaller = $this->getContainer()->get(MarshallerInterface::class);

        $this->assertEquals([
            'group' => [
                'uuid' => '00000000-0000-0000-0000-000000000000',
                'code' => 'simple-code',
                'sort' => 1,
                'access' => 'admin',
                'permissions' => ['foo' => AllowRule::class, 'bar' => ForbidRule::class, 'baz' => true],
                'titles' => [
                    [
                        'title' => 'Some title',
                        'locale' => '00000000-0000-0000-0000-000000000001',
                        'group' => '00000000-0000-0000-0000-000000000000',
                    ],
                ]
            ]
        ], $marshaller->marshal(new GroupWasCreated($group)));
    }

    public function testUnmarshal(): void
    {
        $group = new Group();
        $group->uuid = Uuid::fromString('00000000-0000-0000-0000-000000000000');
        $group->code = 'simple-code';
        $group->sort = 1;
        $group->access = 'admin';
        $group->permissions = ['foo' => AllowRule::class, 'bar' => ForbidRule::class, 'baz' => true];
        $group->setTitle('Some title', Uuid::fromString('00000000-0000-0000-0000-000000000001'));

        $marshaller = $this->getContainer()->get(MarshallerInterface::class);

        $this->assertEquals(new GroupWasCreated($group), $marshaller->unmarshal([
            'group' => [
                'uuid' => '00000000-0000-0000-0000-000000000000',
                'code' => 'simple-code',
                'sort' => 1,
                'access' => 'admin',
                'permissions' => ['foo' => AllowRule::class, 'bar' => ForbidRule::class, 'baz' => true],
                'titles' => [
                    [
                        'title' => 'Some title',
                        'locale' => '00000000-0000-0000-0000-000000000001',
                        'group' => '00000000-0000-0000-0000-000000000000',
                    ],
                ]
            ]
        ], (new \ReflectionClass(GroupWasCreated::class))->newInstanceWithoutConstructor()));
    }
}
