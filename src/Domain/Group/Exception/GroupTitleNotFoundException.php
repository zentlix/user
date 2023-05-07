<?php

declare(strict_types=1);

namespace Zentlix\User\Domain\Group\Exception;

final class GroupTitleNotFoundException extends \InvalidArgumentException
{
    public function __construct(string $locale)
    {
        parent::__construct(\sprintf('The Group title for Locale `%s` not found.', $locale));
    }
}
