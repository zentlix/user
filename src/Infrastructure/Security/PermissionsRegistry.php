<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Security;

final class PermissionsRegistry
{
    /**
     * @var non-empty-string[]
     */
    private array $permission = [];

    /**
     * @param non-empty-string $permission
     */
    public function add(string $permission): void
    {
        $this->permission[] = $permission;
    }

    /**
     * @return non-empty-string[]
     */
    public function getPermissions(): array
    {
        return $this->permission;
    }
}
