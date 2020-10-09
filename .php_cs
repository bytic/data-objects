<?php

$finder = Symfony\Component\Finder\Finder::create()
    ->notPath('vendor')
    ->in([
        __DIR__.'/src',
        __DIR__.'/tests',
    ])
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR12' => true
    ])
    ->setFinder($finder);