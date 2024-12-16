<?php

namespace App\Asset;
use ScssPhp\ScssPhp\Compiler;
use Symfony\Component\AssetMapper\AssetMapperInterface;
use Symfony\Component\AssetMapper\Compiler\AssetCompilerInterface;
use Symfony\Component\AssetMapper\MappedAsset;


class ScssCompiler implements AssetCompilerInterface
{

    public function supports(MappedAsset $asset): bool
    {
        return str_ends_with($asset->logicalPath, 'scss');
    }

    public function compile(string $content, MappedAsset $asset, AssetMapperInterface $assetMapper): string
    {
      $scss = new Compiler();
      return $scss->compileString($content)->getCss();

    }
}