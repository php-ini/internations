<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Validator\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use Internations\AdminBundle\Entity\User;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class DuplicateEmailValidator extends ConstraintValidator
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof DuplicateEmail) {
            throw new UnexpectedTypeException($constraint, DuplicateEmail::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, 'string');
        }

        $find = $this
            ->entityManager
            ->getRepository(User::class)
            ->findBy(['email' => $value]);

        if ($find) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}