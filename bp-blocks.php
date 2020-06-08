<?php
/**
 * Plugin Name:     Bp Blocks
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     PLUGIN DESCRIPTION HERE
 * Author:          YOUR NAME HERE
 * Author URI:      YOUR SITE HERE
 * Text Domain:     bp-blocks
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Bp_Blocks
 */

// Your code starts here.

require_once 'inc/plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/toiee-lab/bp-blocks',
	__FILE__,
	'bp-blocks'
);


require_once 'inc/remove-margin.php';


// スタイルセレクト機能 script
function style_editor( $hook ) {
	wp_enqueue_script(
		'style_js',
		plugins_url( 'js/style.js', __FILE__ ),
		array( 'wp-blocks', 'wp-element', 'wp-hooks' ), // Dependency to include the CSS after it.
		filemtime( plugins_url( 'js/style.js', __FILE__ ). 'js/style.js' ),
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'style_editor' );
 
// スタイルセレクト機能 style
function style_frontend_editor($hook) {
	wp_enqueue_style(
		'style_css',
		plugins_url( 'css/style.css', __FILE__ ),
		array(),
		filemtime( plugin_dir_path( __FILE__ ) . 'css/style.css' ) // filemtime — Gets file modification time.
	);
}
add_action( 'enqueue_block_assets', 'style_frontend_editor' );

/**
 * CSSの出力
 *
 * @return void
 */
function bp_ext_css() {

	$link_color             = get_theme_mod( 'businesspress_link_color', '#4693f5' );
	$light_background_color = get_theme_mod( 'businesspress_light_background_color', '#f4f5f6' );

	$css = '
	<style>
	.wp-block-bp-blocks-bp-subheader {
		color: ' . esc_attr( $link_color ) . ';
		font-weight: 700;
	}
	';

	if( get_theme_mod( 'businesspress_force_light_background_color', true) ) {
		$css .= '
	.wp-block-coblocks-shape-divider,
	.ghostkit-shape-divider {
		color: ' . esc_attr( $light_background_color ) . ' !important;
	}
	';
	}

	$css .= '</style>
	';

	echo $css;
}
add_action( 'wp_head', 'bp_ext_css' );


function bpext_register( $wp_customize ) {
	$wp_customize->add_setting(
		'businesspress_force_light_background_color',
		array(
			'default' => true,
		),
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
add_action( 'customize_register', 'bpext_register' );