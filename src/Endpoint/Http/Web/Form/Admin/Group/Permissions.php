<?php

declare(strict_types=1);

namespace Zentlix\User\Endpoint\Http\Web\Form\Admin\Group;

use Spiral\Security\Rule\AllowRule;
use Spiral\Security\Rule\ForbidRule;

final class Permissions
{
    public static function transformToFormData(array $permissions): array
    {
        $transformed = [];
        foreach ($permissions as $permission => $rule) {
            $transformed[\str_replace('.', ':', $permission)] = $rule === AllowRule::class;
        }

        return $transformed;
    }

    public static function transformToNormalizedData(array $permissions): array
    {
        $transformed = [];
        foreach ($permissions as $permission => $rule) {
            $transformed[\str_replace(':', '.', $permission)] = $rule ? AllowRule::class : ForbidRule::class;
        }

        return $transformed;
    }
}
