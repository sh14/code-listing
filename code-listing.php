<?php
/**
 * Code listing
 *
 * @package           code-listing
 * @author            sh14
 * @license           GPL-2.0-or-later
 * @wordpress-plugin
 *
 * Plugin Name:       Code listing
 * Description:       Code examples beautifier. Use tag CODE with parameters "data-lang" and "data-lang-caption" inside tag PRE.
 * Version:           1.0.0
 * Requires at least: 5.1
 * Requires PHP:      5.6.20
 * Author:            Alex Isaenko
 * Author URI:        https://profiles.wordpress.org/sh14/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       code-listing
 * Domain Path:       /languages/
 * Date: 21.02.2023
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

require __DIR__ . '/AssetsLoader.php';
require __DIR__ . '/shortcode.php';
require __DIR__ . '/OptionsPage.php';
function getPluginUrl(): string
{
    return plugin_dir_url(__FILE__);
}

function textDomain(): string
{
    return 'code-listing';
}

AssetsLoader::init();
new OptionsPage();

// eof