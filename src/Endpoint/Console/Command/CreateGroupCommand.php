<?php

declare(strict_types=1);

namespace Zentlix\User\Endpoint\Console\Command;

use Spiral\Console\Attribute\AsCommand;
use Spiral\Console\Command;
use Spiral\Cqrs\CommandBusInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Zentlix\User\Application\Group\Command\CreateCommand;
use Zentlix\User\Domain\Group\DefaultAccess;
use Zentlix\User\Domain\Locale\ReadModel\Repository\LocaleRepositoryInterface;

/**
 * @property SymfonyStyle $output
 */
#[AsCommand(
    name: 'user:create:group',
    description: 'Given a title, code, group role, and sort generates a new user group.'
)]
final class CreateGroupCommand extends Command
{
    protected function perform(LocaleRepositoryInterface $localeRepository, CommandBusInterface $commandBus): int
    {
        $locales = $localeRepository->findAll();
        if ([] === $locales) {
            $this->output->info('Please, create at least one locale.');

            return self::INVALID;
        }

        $command = new CreateCommand();

        foreach ($locales as $locale) {
            $question = \sprintf('Group title [%s]', $locale->getFullCode());
            /** @var non-empty-string $title */
            $title = $this->output->ask($question, null, function (mixed $title): string {
                if (!\is_string($title) || empty($title)) {
                    throw new \InvalidArgumentException('Title cannot be empty.');
                }

                return $title;
            });
            $command->data->setTitle($title, $locale->uuid);
        }

        /** @var non-empty-string $code */
        $code = $this->output->ask('Group symbol code', null, function (mixed $code) {
            if (!\is_string($code) || empty($code)) {
                throw new \InvalidArgumentException('Symbol code cannot be empty.');
            }

            return $code;
        });
        $command->data->code = $code;
        /** @var non-empty-string $access */
        $access = $this->output->choice(
            'Please, select a group access',
            [DefaultAccess::Admin->value, DefaultAccess::User->value],
            0
        );
        $command->data->access = $access;
        /** @var positive-int $sort */
        $sort = $this->output->ask('Group sort', '1', function (mixed $sort) {
            if (empty($sort) || (int) $sort < 1) {
                throw new \InvalidArgumentException('Group sort must be a positive int.');
            }

            return (int) $sort;
        });
        $command->data->sort = $sort;

        try {
            $commandBus->dispatch($command);
        } catch (\Throwable $exception) {
            $this->output->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->output->success('User group was created!');

        foreach ($command->data->getTitles() as $title) {
            foreach ($locales as $locale) {
                if ($locale->uuid->equals($title->getLocale())) {
                    $this->output->text(\sprintf('Title [%s]: %s', $locale->getFullCode(), $title->title));
                }
            }
        }
        $this->output->text([
            \sprintf('Symbol code: %s', $command->data->code),
            \sprintf('Access: %s', $command->data->access),
            \sprintf('Sort: %s', $command->data->sort),
        ]);

        return self::SUCCESS;
    }
}
