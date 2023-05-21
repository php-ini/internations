<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class DuplicateEmail extends Constraint
{
    public $message = 'Email already exists.';
}