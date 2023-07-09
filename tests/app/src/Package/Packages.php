<?php

declare(strict_types=1);

namespace Tests\App\User\Package;

use Composer\InstalledVersions;
use Zentlix\Core\Infrastructure\Package\Service\Packages as BasePackages;

class Packages extends BasePackages
{
    public function getInstalled(): array
    {
        return [
            'zentlix/core',
            'zentlix/user',
        ];
    }

    public function getInstallPath(string $package): string
    {
        if ((bool) env('MONOREPO_TESTING')) {
            return match ($package) {
                'zentlix/core' => \dirname(__DIR__, 5) . '/Core',
                'zentlix/user' => \dirname(__DIR__, 5) . '/User',
            };
        }

        return InstalledVersions::getInstallPath($package);
    }
}
