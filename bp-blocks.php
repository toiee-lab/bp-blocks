<?php
/**
 * Plugin Name:     Bp Blocks
 * Plugin URI:      https://github.com/toiee-lab/bp-blocks
 * Description:     BusinessPressを、もっと使いやすくするためのプラグイン
 * Author:          toiee Lab
 * Author URI:      https://toiee.jp
 * Text Domain:     bp-blocks
 * Domain Path:     /languages
 * Version:         1.3
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

global $bpb_plugin_file_path;
$bpb_plugin_file_path = __FILE__;

global $bpb_plugin_dir_path;
$bpb_plugin_dir_path = plugin_dir_path( __FILE__ );

require_once 'inc/tgmpa.php';

require_once 'inc/remove-margin.php';

require_once 'inc/settings.php';

require_once 'inc/styling.php';

require_once 'inc/wc-login-popup.php';
