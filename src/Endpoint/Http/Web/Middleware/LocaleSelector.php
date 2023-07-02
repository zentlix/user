<?php

declare(strict_types=1);

namespace Zentlix\User\Endpoint\Http\Web\Middleware;

use Cycle\ORM\ORMInterface;
use Cycle\ORM\SchemaInterface;
use Cycle\ORM\Service\RepositoryProviderInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zentlix\Core\Infrastructure\Shared\ReadModel\Repository\CycleRepository;
use Zentlix\User\Domain\Locale\ReadModel\LocaleView;
use Zentlix\User\Infrastructure\Translator\Translator;

final class LocaleSelector implements MiddlewareInterface
{
    /** @var array<non-empty-string, LocaleView> */
    private array $availableLocales;

    private RepositoryProviderInterface $repositoryProvider;

    public function __construct(
        private readonly Translator $translator,
        private readonly SchemaInterface $schema,
        ORMInterface $orm
    ) {
        foreach ($this->translator->getAvailableLocales() as $locale) {
            $this->availableLocales[$locale->code] = $locale;
        }

        $this->repositoryProvider = $orm->getService(RepositoryProviderInterface::class);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $defaultLocale = $this->translator->getLocale();

        try {
            foreach ($this->fetchLocalesFromHeaders($request) as $locale) {
                if ($locale !== '' && isset($this->availableLocales[$locale])) {
                    $this->translator->setLocale($locale);
                    $this->translator->setLocaleView($this->availableLocales[$locale]);
                    foreach ($this->schema->getRoles() as $role) {
                        $repository = $this->repositoryProvider->getRepository($role);
                        if ($repository instanceof CycleRepository) {
                            $repository->setCurrentLocale($this->availableLocales[$locale]);
                        }
                    }
                    break;
                }
            }
            return $handler->handle($request);
        } finally {
            // restore
            $this->translator->setLocale($defaultLocale);
            $this->translator->setLocaleView($this->availableLocales[$defaultLocale]);
        }
    }

    public function fetchLocalesFromHeaders(ServerRequestInterface $request): \Generator
    {
        $header = $request->getHeaderLine('accept-language');
        foreach (\explode(',', $header) as $value) {
            $pos = \strpos($value, ';');
            if ($pos!== false) {
                yield \substr($value, 0, $pos);
            }

            yield $value;
        }
    }
}
