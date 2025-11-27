<?php
/**
 * Code listing
 *
 * Plugin Name:       Code listing
 * Description:       Code examples beautifier. Use tag CODE with parameters "data-lang" and "data-lang-caption" inside tag PRE.
 * Version:           1.2.0
 * Requires at least: 5.1
 * Requires PHP:      5.6.20
 * Author:            Alex Isaenko
 * Author URI:        https://profiles.wordpress.org/sh14/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       code-listing
 * Domain Path:       /languages/
 * Date:              21.02.2023
 * @package           code-listing
 * @author            sh14
 * @license           GPL-2.0-or-later
 * @wordpress-plugin
 *
 */

/*
 * Basic use example:
<pre>
        <code>
            # unknown code example
            echo("Hello, World!")
        </code>

        <code data-lang="python">
            # Python code example
            print("Hello, World!")
        </code>

        <code data-lang="curl" data-lang-caption="cURL">
            # CURL code example
            curl https://example.com
        </code>
</pre>
 * */

namespace codeListing;

use Helpers\Debug;

require __DIR__ . '/AssetsLoader.php';
require __DIR__ . '/OptionsPage.php';
require __DIR__ . '/Block.php';

function getPluginUrl(): string {
	return plugin_dir_url( __FILE__ );
}

function getPluginVer(): string {
	// get_plugin_data works for admin panel only, that's why we need to check existence
	if ( ! function_exists( 'get_plugin_data' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}
	$data = get_plugin_data( __FILE__ );
	if ( ! empty( $data['Version'] ) ) {
		return $data['Version'];
	}

	return '';
}

function textDomain(): string {
	return 'code-listing';
}

AssetsLoader::init();
new OptionsPage();
Block::init();

// eof