<?php

declare(strict_types=1);

namespace Zentlix\User\Infrastructure\Group\Service;

use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Zentlix\Core\Domain\Shared\Exception\DomainException;
use Zentlix\User\Domain\Group\DataTransferObject\Group as GroupDTO;
use Zentlix\User\Domain\Group\DefaultGroups;
use Zentlix\User\Domain\Group\Exception\DuplicateCodeException;
use Zentlix\User\Domain\Group\Exception\GroupValidationException;
use Zentlix\User\Domain\Group\Group;
use Zentlix\User\Domain\Group\Service\GroupValidatorInterface;
use Zentlix\User\Domain\Group\Specification\UniqueCodeSpecificationInterface;
use Zentlix\User\Domain\Locale\Exception\LocaleNotFoundException;
use Zentlix\User\Domain\Locale\Specification\ExistsLocaleSpecificationInterface;
use Zentlix\User\Domain\Translator\TranslatorInterface;

readonly class GroupValidator implements GroupValidatorInterface
{
    public function __construct(
        protected ValidatorInterface $validator,
        protected UniqueCodeSpecificationInterface $uniqueCodeSpecification,
        protected ExistsLocaleSpecificationInterface $existsLocaleSpecification,
        protected TranslatorInterface $translator
    ) {
    }

    /**
     * @throws GroupValidationException
     * @throws DuplicateCodeException
     * @throws LocaleNotFoundException
     */
    public function preCreate(GroupDTO $data): void
    {
        $errors = $this->validator->validate($data);
        if ($errors->count() > 0) {
            throw new GroupValidationException($errors);
        }

        $this->uniqueCodeSpecification->isUnique($data->code);

        $this->validateTitles($data);
    }

    /**
     * @throws GroupValidationException
     * @throws DuplicateCodeException
     * @throws LocaleNotFoundException
     * @throws DomainException
     */
    public function preUpdate(GroupDTO $data, Group $group): void
    {
        $errors = $this->validator->validate($data);
        if ($errors->count() > 0) {
            throw new GroupValidationException($errors);
        }

        if ($group->getCode() !== $data->code) {
            $this->uniqueCodeSpecification->isUnique($data->code);
        }

        if ($group->getCode() !== $data->code && $this->isDefaultGroup($group)) {
            throw new DomainException($this->translator->trans('user.group.cant_change_default_group_code'));
        }

        if ($group->getAccess() !== $data->access && $this->isDefaultGroup($group)) {
            throw new DomainException($this->translator->trans('user.group.cant_change_default_group_access'));
        }

        $this->validateTitles($data);
    }

    /**
     * @throws GroupValidationException
     * @throws LocaleNotFoundException
     */
    protected function validateTitles(GroupDTO $data): void
    {
        foreach ($data->getTitles() as $title) {
            $errors = $this->validator->validate($title);
            if ($errors->count() > 0) {
                throw new GroupValidationException($errors);
            }

            if (!$title->getGroup()->equals($data->uuid)) {
                throw new GroupValidationException(
                    ConstraintViolationList::createFromMessage('Invalid User group UUID.')
                );
            }

            $this->existsLocaleSpecification->isExists($title->getLocale());
        }
    }

    protected function isDefaultGroup(Group $group): bool
    {
        return \in_array(
            $group->getCode(),
            \array_map(static fn (DefaultGroups $group): string => $group->value, DefaultGroups::cases()),
            true
        );
    }
}
