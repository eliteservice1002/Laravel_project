<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/app')
    ->in(__DIR__.'/config')
    ->in(__DIR__.'/database')
    ->in(__DIR__.'/public')
    ->in(__DIR__.'/resources/lang')
    ->in(__DIR__.'/resources/views')
    ->in(__DIR__.'/routes');

$config = new \PhpCsFixer\Config();

return $config
    ->setRules([
        '@PSR2' => true,
        '@PSR12' => true,
        '@PhpCsFixer' => true,
        '@PHP80Migration' => true,
        'yoda_style' => false,
        'cast_spaces' => true,
        'phpdoc_align' => ['align' => 'left'],
        'phpdoc_to_comment' => false,
        'not_operator_with_space' => true,
        'trailing_comma_in_multiline_array' => true,
        'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line'],
    ])
    ->setFinder($finder);
