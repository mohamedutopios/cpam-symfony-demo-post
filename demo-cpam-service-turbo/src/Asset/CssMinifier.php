<?php

namespace App\Asset;

use Symfony\Component\AssetMapper\AssetMapperInterface;
use Symfony\Component\AssetMapper\Compiler\AssetCompilerInterface;
use Symfony\Component\AssetMapper\MappedAsset;
use MatthiasMullie\Minify\CSS;

class CssMinifier implements AssetCompilerInterface
{

    public function supports(MappedAsset $asset): bool
    {
        return str_ends_with($asset->logicalPath, 'css');
    }

    public function compile(string $content, MappedAsset $asset, AssetMapperInterface $assetMapper): string
    {
        $minifier = new CSS();
        $minifier -> add($content);
        return $minifier -> minify();

    }
}