<?php
/**
 * BusinessPress 用のブロックを有効にするためのプログラム
 * 
 */

 /**
  * スタイルセレクト機能 script
  *
  * @param [type] $hook
  * @return void
  */
function bpb_style_editor( $hook ) {
	global $bpb_plugin_file_path;

	wp_enqueue_script(
		'style_js',
		plugins_url( 'js/style.js', $bpb_plugin_file_path ),
		array( 'wp-blocks', 'wp-element', 'wp-hooks' ), // Dependency to include the CSS after it.
		filemtime( plugin_dir_path( $bpb_plugin_file_path ) . 'js/style.js' ),
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'bpb_style_editor' );

/**
 * スタイルセレクト機能 style
 *
 * @param [type] $hook
 * @return void
 */
function bpb_style_frontend_editor( $hook ) {
	global $bpb_plugin_file_path;

	wp_enqueue_style(
		'style_css',
		plugins_url( 'css/style.css', $bpb_plugin_file_path ),
		array(),
		filemtime( plugin_dir_path( $bpb_plugin_file_path ) . 'css/style.css' ) // filemtime — Gets file modification time.
	);
}
add_action( 'enqueue_block_assets', 'bpb_style_frontend_editor' );

/**
 * CSSの出力
 *
 * @return void
 */
function bpb_ext_css() {

	$link_color             = get_theme_mod( 'businesspress_link_color', '#4693f5' );
	$link_color_hover       = get_theme_mod( 'businesspress_link_hover_color', '#639af6' );
	$light_background_color = get_theme_mod( 'businesspress_light_background_color', '#f4f5f6' );

	$css = '
	<style>
	.wp-block-bp-blocks-bp-subheader {
		color: ' . esc_attr( $link_color ) . ';
	}
	';

	if( get_theme_mod( 'businesspress_force_light_background_color', true) ) {
		$css .= '
	.wp-block-coblocks-shape-divider,
	.ghostkit-shape-divider {
		color: ' . esc_attr( $light_background_color ) . ' !important;
	}

	/* for woocommerce */
	.product.featured {
		background-color: inherit;
		border-radius: inherit;
		color: inherit;
		display: inline-block;
		font-size: inherit;
		margin-bottom: inherit;
		margin-right: inherit;
		padding: inherit;
		text-transform: inherit;
	}

	.woocommerce ul.products li.product .price,
	.woocommerce div.product p.price,
	.woocommerce div.product span.price {
		color: ' . esc_attr( $link_color ) . ';
	}

	.woocommerce span.onsale,
	.woocommerce #respond input#submit.alt,
	.woocommerce a.button.alt,
	.woocommerce button.button.alt,
	.woocommerce input.button.alt {
		background-color: ' . esc_attr( $link_color ) . ';
	}

	.woocommerce #respond input#submit.alt:hover,
	.woocommerce a.button.alt:hover,
	.woocommerce button.button.alt:hover,
	.woocommerce input.button.alt:hover {
		background-color: ' . esc_attr( $link_color_hover ) . ';
	}
	';
	}

	$css .= '</style>
	';

	echo $css;
}
add_action( 'wp_head', 'bpb_ext_css' );


function bpb_ext_register( $wp_customize ) {
	$wp_customize->add_setting(
		'businesspress_force_light_background_color',
		array(
			'default' => true,
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'businesspress_force_light_background_color',
			array(
				'label'    => 'シェイプ区切り色の自動設定',
				'description' => '薄い背景色の色を使ってシェイプ区切りの色を自動で設定します',
				'section'  => 'colors',
				'priority' => 4,
				'type'     => 'checkbox',
				'setting'  => 'businesspress_force_light_background_color'
			)
		)
	);
}
add_action( 'customize_register', 'bpb_ext_register' );
