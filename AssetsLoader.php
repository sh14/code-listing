<?php

namespace codeListing;

class AssetsLoader
{
    private static $load = false;

    public static function init(): void
    {
        add_action('wp_footer', [self::class, 'enqueue']);
    }

    public static function load(): void
    {
        self::$load = true;
    }

    public static function enqueue(): void
    {
        $jsData = [
            'copy'   => __('Copy', textDomain()),
            'copied' => __('Copied', textDomain()),
        ];
        $jsData = array_map(static function ($key,$value){
            return "$key:'$value'";
        },array_keys($jsData),$jsData);
        $jsData = implode(',',( $jsData));
        if (self::$load) {
            echo '<link rel="stylesheet" href="' . getPluginUrl() . 'assets/css/styles.css"/>' . PHP_EOL;
            echo '<script>const codeListing = {' . $jsData . '}</script>';

            echo '<script src="' . getPluginUrl() . 'assets/js/code-listing.js"></script>' . PHP_EOL;
        }
    }

}

// eof