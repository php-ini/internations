<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Dto\Response\Transformer;

abstract class AbstractResponseDtoTransformer implements ResponseDtoTransformerInterface
{
    public function transformFromObjects(iterable $objects, $isNested = false): iterable
    {
        $dto = [];

        foreach($objects as $object) {
            $dto[] = $this->transformFromObject($object, $isNested);
        }

        return $dto;
    }
}