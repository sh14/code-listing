<?php

namespace codeListing;

function getScheme(): string
{
    $scheme = get_option('code_listing', true);
    if ( ! empty($scheme)) {
        return ' class="' . $scheme . '"';
    }

    return '';
}

function replaceTrash(string $content): string
{
    return str_replace(['<br />', '<br/>', '<br>', '<p>', '</p>'], ['', '', '', '', "\n"], $content);
}

function codeBlock($atts = [], string $content = ''): string
{
    $content = replaceTrash($content);
    preg_match_all('/\[code(.*?)\]/', $content, $matches);
    $count_to_replace = 1;
    foreach ($matches[0] as $index => $code) {
        $replace = '[code index="' . ($index + 1) . '"' . $matches[1][$index] . ']';
        $content = str_replace($code, $replace, $content, $count_to_replace);
    }

    return '<pre' . getScheme() . '>' . do_shortcode($content) . '</pre>';
}

add_shortcode('codeset', __NAMESPACE__ . '\codeBlock');
function code($atts, string $content = ''): string
{
    // allow assets loading
    AssetsLoader::load();
    $content    = replaceTrash($content);
    $atts       = shortcode_atts([
        'lang'    => '',
        'caption' => '',
        'index'   => 0,
    ], $atts);
    $names      = [
        'lang'    => 'data-lang',
        'caption' => 'data-lang-caption',
        'index'   => 'data-code-index',
    ];
    $attributes = [];
    foreach ($names as $key => $name) {
        if ( ! empty($atts[$key])) {
            $attributes[] = $name . '="' . $atts[$key] . '"';
        }
    }
    $attributes = implode(' ', $attributes);
    if (empty($atts['index'])) {
        $before = '<pre' . getScheme() . '>';
        $after  = '</pre>';
    } else {
        $before = '';
        $after  = '';
    }

    return "$before<code $attributes>$content</code>$after";
}

add_shortcode('code', __NAMESPACE__ . '\code');

// eof