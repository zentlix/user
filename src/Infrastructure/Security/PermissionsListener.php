<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Security;

use Spiral\Attributes\ReaderInterface;
use Spiral\Domain\Annotation\Guarded;
use Spiral\Domain\Annotation\GuardNamespace;
use Spiral\Tokenizer\TokenizationListenerInterface;

final class PermissionsListener implements TokenizationListenerInterface
{
    public function __construct(
        private readonly PermissionsRegistry $registry,
        private readonly ReaderInterface $reader
    ) {
    }

    public function listen(\ReflectionClass $class): void
    {
        $namespace = $this->reader->firstClassMetadata($class, GuardNamespace::class);

        foreach ($class->getMethods() as $method) {
            $guarded = $this->reader->firstFunctionMetadata($method, Guarded::class);
            if ($guarded !== null) {
                $this->registry->add($this->makePermission($method, $guarded, $namespace));
            }
        }
    }

    public function finalize(): void
    {
    }

    private function makePermission(
        \ReflectionMethod $method,
        ?Guarded $guarded = null,
        ?GuardNamespace $namespace = null
    ): string {
        $permission = $guarded === null || empty($guarded->permission)
            ? $method->getName()
            : $guarded->permission;

        if ($namespace !== null) {
            $permission = $namespace->namespace . '.' . $permission;
        }

        return $permission;
    }
}
