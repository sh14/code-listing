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
		// Подготовка данных для JS
		$jsData = [
			'copy'   => __('Copy', textDomain()),
			'copied' => __('Copied', textDomain()),
			'theme'  => get_option('code_listing', true),
		];

		// Регистрируем стили
		wp_enqueue_style(
			'code-listing-styles',
			getPluginUrl() . 'assets/css/styles.css',
			[],
			getPluginVer()
		);

		// Регистрируем скрипт
		wp_enqueue_script(
			'code-listing-script',
			getPluginUrl() . 'assets/js/code-listing.js',
			[],
			getPluginVer(),
			true // Скрипт подключаем внизу страницы
		);

		// Локализуем данные для JS
		wp_localize_script(
			'code-listing-script',
			'codeListing',
			$jsData
		);
	}


}

// eof