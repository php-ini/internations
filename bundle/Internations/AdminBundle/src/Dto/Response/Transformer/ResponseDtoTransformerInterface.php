<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Dto\Response\Transformer;

interface ResponseDtoTransformerInterface
{
    public function transformFromObject($object, $isNested = false);

    public function transformFromObjects(iterable $objects, bool $isNested = false): iterable;
}