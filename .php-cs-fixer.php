<?php

/**
 * To strictly follow the PSRs.
 *
 * @see source https://gist.github.com/srbrunoferreira/5b0d96955c3913f6b1cd805c2a14079d
 * @see rules https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/rules/index.rst
 * @see php-cs-fixer rule sets https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/ruleSets/index.rst
 */

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        '@PSR2' => true,
        '@PSR1' => true,
        'array_indentation' => true,
        'array_syntax' => ['syntax' => 'short'],
        'combine_consecutive_unsets' => true,
        'class_attributes_separation' => ['elements' => ['method' => 'one']],
        'multiline_whitespace_before_semicolons' => false,
        'single_quote' => true,
        'visibility_required' => true,
        'ordered_imports' => ['imports_order' => ['class', 'function', 'const'], 'sort_algorithm' => 'none'],

        'binary_operator_spaces' => [
            'operators' => []
        ],
        'braces' => [
            'allow_single_line_closure' => true,
        ],
        'concat_space' => ['spacing' => 'one'],
        'declare_equal_normalize' => true,
        'function_typehint_space' => true,
        'single_line_comment_style' => ['comment_types' => ['hash']],
        'include' => true,
        'lowercase_cast' => true,
        'lowercase_static_reference' => true,
        'native_function_casing' => true,
        'no_blank_lines_before_namespace' => false,
        'blank_lines_before_namespace' => true,
        'no_empty_phpdoc' => true,
        'no_empty_statement' => true,
        'no_extra_blank_lines' => [
            'tokens' => [
                'curly_brace_block',
                'extra',
                'throw',
                'use',
            ]
        ],
        'no_multiline_whitespace_around_double_arrow' => true,
        'no_spaces_around_offset' => true,
        'no_trailing_comma_in_list_call' => true,
        'no_trailing_comma_in_singleline_array' => true,
        'no_unused_imports' => true,
        'no_whitespace_before_comma_in_array' => true,
        'no_whitespace_in_blank_line' => true,
        'object_operator_without_whitespace' => true,
        'phpdoc_align' => ['align' => 'vertical'],
        'phpdoc_no_empty_return' => false,
        'phpdoc_no_useless_inheritdoc' => true,
        'phpdoc_scalar' => true,
        'phpdoc_separation' => false,
        'phpdoc_trim' => true,
        'phpdoc_types' => true,
        'return_type_declaration' => true,
        'short_scalar_cast' => true,
        'single_blank_line_before_namespace' => false,
        'ternary_operator_spaces' => true,
        'trailing_comma_in_multiline' => ['elements' => [], 'after_heredoc' => false],
        'single_trait_insert_per_statement' => true,
        'single_import_per_statement' => ['group_to_single_imports' => false],
        'trim_array_spaces' => true,
        'unary_operator_spaces' => true,
        'whitespace_after_comma_in_array' => true,
        'space_after_semicolon' => true,
        'single_blank_line_at_eof' => true,

        'yoda_style' => [
            'always_move_variable' => false,
            'equal' => false,
            'identical' => false,
            'always_move_variable' => false,
        ],

        // Risk
        'modernize_types_casting' => true,
        'strict_comparison' => true,
        'declare_strict_types' => false,
        'strict_param' => true
    ])
    ->setIndent('    ')
    ->setLineEnding("\n")
    ->setRiskyAllowed(true)
;
