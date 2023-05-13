<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Shared\Config;

use Spiral\Core\InjectableConfig;

/**
 * @property array{
 *     password_hashers: array<class-string, array>
 * } $config
 */
final class UserConfig extends InjectableConfig
{
    public const CONFIG = 'user';

    protected array $config = [
        'password_hashers' => [],
    ];

    /**
     * @return array<class-string, array>
     */
    public function getPasswordHashers(): array
    {
        return $this->config['password_hashers'] ?? [];
    }
}
