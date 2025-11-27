<?php
/**
 * Date: 7/11/25
 * @author Isaenko Aleksei <aisaenko2023@gmail.com>
 */


namespace codeListing;

class Block {
	public static function init(): void {
		add_action( 'init', [ __CLASS__, 'register_block' ] );
		add_action( 'enqueue_block_editor_assets', [ __CLASS__, 'enqueue' ] );
	}

	public static function enqueue(): void {
		wp_enqueue_script(
			'oi-code-block-editor',
			getPluginUrl() . 'assets/js/editor.js',
			[ 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-block-editor' ],
			getPluginVer(),
			true
		);
	}

	public static function register_block(): void {
		register_block_type( 'oi/code-block', [
			"apiVersion"      => 2,
			"name"            => "oi/code-block",
			"title"           => "Code Block with Data Attributes",
			"category"        => "formatting",
			"icon"            => "editor-code",
			"description"     => "Блок кода с data-lang и data-lang-caption",
			'editor_script'   => 'oi-code-block-editor',
			'render_callback' => [ __CLASS__, 'oi_render_code_block' ],
			'attributes'      => [
				'content'         => [
					'type'    => 'string',
					'default' => '',
				],
				'dataLang'        => [
					'type'    => 'string',
					'default' => '',
				],
				'dataLangCaption' => [
					'type'    => 'string',
					'default' => '',
				],
			],
		] );
	}


	public static function oi_render_code_block( $attributes ) {
		$content      = isset( $attributes['content'] ) ? $attributes['content'] : '';
		$data_lang    = isset( $attributes['dataLang'] ) ? esc_attr( $attributes['dataLang'] ) : '';
		$data_caption = isset( $attributes['dataLangCaption'] ) ? esc_attr( $attributes['dataLangCaption'] ) : '';

		$attrs = '';
		if ( $data_lang ) {
			$attrs .= ' lang="' . $data_lang . '"';
		}
		if ( $data_caption ) {
			$attrs .= ' caption="' . $data_caption . '"';
		}

		return '<pre><code' . $attrs . '>' . esc_html( $content ) . '</code></pre>';
	}


}

// eof