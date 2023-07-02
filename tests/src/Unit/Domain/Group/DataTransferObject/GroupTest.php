<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Group\DataTransferObject;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Spiral\Attributes\AttributeReader;
use Spiral\Marshaller\Mapper\AttributeMapperFactory;
use Spiral\Marshaller\Marshaller;
use Spiral\Security\Rule\AllowRule;
use Spiral\Security\Rule\ForbidRule;
use Zentlix\User\Domain\Group\DataTransferObject\Group;

final class GroupTest extends TestCase
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

        $marshaller = new Marshaller(new AttributeMapperFactory(new AttributeReader()));

        $this->assertEquals([
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
        ], $marshaller->marshal($group));
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

        $marshaller = new Marshaller(new AttributeMapperFactory(new AttributeReader()));

        $this->assertEquals($group, $marshaller->unmarshal([
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
        ], new Group()));
    }
}
