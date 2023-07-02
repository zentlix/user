<?php

declare(strict_types=1);

namespace Zentlix\User\Endpoint\Console\Command;

use Spiral\Console\Attribute\Argument;
use Spiral\Console\Attribute\AsCommand;
use Spiral\Console\Command;
use Spiral\Cqrs\CommandBusInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Zentlix\User\Application\User\Command\CreateCommand;
use Zentlix\User\Domain\Group\ReadModel\GroupView;
use Zentlix\User\Domain\Group\ReadModel\Repository\GroupRepositoryInterface;
use Zentlix\User\Domain\User\Status;

/**
 * @property SymfonyStyle $output
 */
#[AsCommand(
    name: 'user:create:user',
    description: 'Given an email, password, and name generates a new user.'
)]
final class CreateUserCommand extends Command
{
    /**
     * @var non-empty-string
     */
    #[Argument(description: 'Email address')]
    private string $email;

    protected function perform(CommandBusInterface $commandBus, GroupRepositoryInterface $groupRepository): int
    {
        $groups = \array_map(
            static fn (GroupView $group) => [
                'uuid' => $group->uuid->toString(),
                'code' => $group->code,
            ],
            $groupRepository->findAll()
        );
        if ([] === $groups) {
            $this->output->info('Please, create at least one user group.');

            return self::INVALID;
        }

        $command = new CreateCommand();

        $command->data->setEmail($this->email);

        /** @var non-empty-string $password */
        $password = $this->output->ask('Password', null, function (mixed $password) {
            if (!\is_string($password) || empty($password)) {
                throw new \InvalidArgumentException('Password cannot be empty.');
            }

            return $password;
        });
        $command->data->password = $password;

        /** @var non-empty-string $group */
        $group = $this->output->choice('Please, select group', \array_column($groups, 'code', 'uuid'));

        $command->data->setGroups([$group]);
        $phone = (string) $this->output->ask('Phone');
        if (!empty($phone)) {
            $command->data->setPhone($phone);
        }
        /** @var non-empty-string|null $firstName */
        $firstName = (string) $this->output->ask('First name');
        $command->data->firstName = $firstName;

        /** @var non-empty-string|null $lastName */
        $lastName = (string) $this->output->ask('Last name');
        $command->data->lastName = $lastName;

        /** @var non-empty-string|null $middleName */
        $middleName = (string) $this->output->ask('Middle name');
        $command->data->middleName = $middleName;

        $command->data->status = Status::from((string) $this->output->choice('Please, select user status', [
            Status::Active->value,
            Status::Blocked->value,
            Status::Waiting->value,
        ], 0));

        try {
            $commandBus->dispatch($command);
        } catch (\Throwable $exception) {
            $this->output->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->output->success('User created successfully');

        return self::SUCCESS;
    }
}
