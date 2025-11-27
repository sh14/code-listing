<?php

namespace codeListing;

class AssetsLoader
{
    public static function init(): void
    {
        add_action('wp_footer', [self::class, 'enqueue']);
    }

    public static function enqueue(): void
    {
        $jsData = [
            'copy'         => __('Copy', textDomain()),
            'copied'       => __('Copied', textDomain()),
            'theme' => get_option('code_listing', true),
        ];
        $jsData = array_map(static function ($key, $value) {
            return "$key:'$value'";
        }, array_keys($jsData), $jsData);
        $jsData = implode(',', ($jsData));
            echo '<link rel="stylesheet" href="' . getPluginUrl() . 'assets/css/styles.css?v='.getPluginVer().'"/>' . PHP_EOL;
            echo '<script>const codeListing = {' . $jsData . '}</script>';

            echo '<script src="' . getPluginUrl() . 'assets/js/code-listing.js?v='.getPluginVer().'"></script>' . PHP_EOL;
    }

}

// eof