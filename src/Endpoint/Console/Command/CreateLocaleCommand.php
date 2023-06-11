<?php

declare(strict_types=1);

namespace Zentlix\User\Endpoint\Console\Command;

use Spiral\Console\Attribute\Argument;
use Spiral\Console\Attribute\AsCommand;
use Spiral\Console\Attribute\Question;
use Spiral\Console\Command;
use Spiral\Cqrs\CommandBusInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Zentlix\Core\Domain\Shared\Exception\DomainException;
use Zentlix\User\Application\Locale\Command\CreateCommand;

/**
 * @property SymfonyStyle $output
 */
#[AsCommand(
    name: 'user:create:locale',
    description: 'Given a title, code, country code, sort generates a new Locale.'
)]
final class CreateLocaleCommand extends Command
{
    /**
     * @var non-empty-string
     */
    #[Argument(description: 'Locale title')]
    #[Question(question: 'Please, provide the Locale title')]
    private string $title;

    protected function perform(CommandBusInterface $commandBus): int
    {
        $command = new CreateCommand();
        $command->data->title = $this->title;

        /** @var non-empty-string $code */
        $code = (string) $this->output->ask(
            question: 'Locale code',
            validator: function (mixed $code): string {
                if (!\is_string($code) || empty($code)) {
                    throw new \RuntimeException('Locale code cannot be empty.');
                }

                return $code;
            }
        );
        $command->data->setCode($code);

        /** @var non-empty-string $countryCode */
        $countryCode = (string) $this->output->ask(
            question: 'Country code',
            validator: function (mixed $code): string {
                if (!\is_string($code) || empty($code)) {
                    throw new \RuntimeException('Country code cannot be empty.');
                }

                return $code;
            }
        );
        $command->data->setCountryCode($countryCode);

        /** @psalm-suppress InvalidArrayOffset */
        $command->data->active = (bool) $this->output->choice('Locale is active?', [true => 'Yes', false => 'No'], true);

        /** @var positive-int $sort */
        $sort = (int) $this->output->ask(
            question: 'Locale sort',
            default: '1',
            validator: function (mixed $sort): int {
                if ((int) $sort < 1) {
                    throw new \RuntimeException('Locale sort must be a positive int.');
                }

                return (int) $sort;
            }
        );
        $command->data->sort = $sort;

        try {
            $commandBus->dispatch($command);
        } catch (DomainException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->output->success('Locale was created!');
        $this->output->text([
            \sprintf('Title: %s', $command->data->title),
            \sprintf('Code: %s', $command->data->getCode()),
            \sprintf('Country code: %s', $command->data->getCountryCode()),
            \sprintf('Sort: %s', $command->data->sort),
        ]);

        return self::SUCCESS;
    }
}
