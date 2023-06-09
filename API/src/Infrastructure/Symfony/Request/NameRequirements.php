<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Request;

use Symfony\Component\Validator\Constraints\Compound;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Type;

class NameRequirements extends Compound
{
    protected function getConstraints(array $options): array
    {
        return [
            new Type('string'),
            new Length(
                min: 2,
                max: 64,
                minMessage: 'Your name must be at least {{ limit }} characters long',
                maxMessage: 'Your name cannot be longer than {{ limit }} characters',
            ),
        ];
    }
}
