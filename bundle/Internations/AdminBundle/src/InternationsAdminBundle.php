<?php

declare(strict_types=1);

namespace Internations\AdminBundle;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Internations\AdminBundle\DependencyInjection\InternationsAdminBundleExtension;

class InternationsAdminBundle extends Bundle
{
    public function getPath(): string
    {
        return dirname(__DIR__);
    }

    public function getContainerExtension(): ?ExtensionInterface
    {
        return new InternationsAdminBundleExtension();
    }
}