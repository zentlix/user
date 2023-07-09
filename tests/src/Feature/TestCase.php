<?php

declare(strict_types=1);

namespace Tests\User\Feature;

use Spiral\Bootloader\Http\RouterBootloader;
use Spiral\Core\Container;
use Spiral\Nyholm\Bootloader\NyholmBootloader;
use Spiral\Testing\TestableKernelInterface;
use Spiral\Testing\TestCase as BaseTestCase;
use Tests\App\User\Package\Packages;
use Zentlix\Core\Domain\Package\Service\PackagesInterface;
use Zentlix\Core\Infrastructure\Shared\Bootloader\CoreBootloader;
use Zentlix\User\Infrastructure\Shared\Bootloader\UserBootloader;

abstract class TestCase extends BaseTestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->cleanUpRuntimeDirectory();
    }

    public function rootDirectory(): string
    {
        return \dirname(__DIR__, 2);
    }

    public function createAppInstance(Container $container = new Container()): TestableKernelInterface
    {
        $container->bindSingleton(PackagesInterface::class, Packages::class);

        return parent::createAppInstance($container);
    }

    public function defineBootloaders(): array
    {
        return [
            NyholmBootloader::class,
            RouterBootloader::class,
            CoreBootloader::class,
            UserBootloader::class,
        ];
    }
}
