<?php

namespace MoncareyWS\ContentWidgetsBundle;

use MoncareyWS\FoundationBundle\Bundle\BundleHasAssetsToBuild;
use MoncareyWS\FoundationBundle\Bundle\BundleHasNodeModules;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ContentWidgetsBundle extends Bundle implements BundleHasNodeModules, BundleHasAssetsToBuild
{
    public function getGulpSassPaths(): array
    {
        return [
            'src/scss',
            'node_modules/trumbowyg/dist/ui/sass'
        ];
    }
}
