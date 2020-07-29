<?php

/**
 * Generated by the WordPress Option Page generator
 * at http://jeremyhixon.com/wp-tools/option-page/
 */

class BpBlocks {
	private $bp_blocks_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'bp_blocks_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'bp_blocks_page_init' ) );
	}

	public function bp_blocks_add_plugin_page() {
		add_options_page(
			'bp-blocks', // page_title
			'bp-blocks', // menu_title
			'manage_options', // capability
			'bp-blocks', // menu_slug
			array( $this, 'bp_blocks_create_admin_page' ) // function
		);
	}

	public function bp_blocks_create_admin_page() {
		$this->bp_blocks_options = get_option( 'bp_blocks_option_name' ); 

		if ( isset( $_POST['cmd'] ) && 'bp-blocks-settings' === $_POST['cmd'] ) {
			check_admin_referer( 'bp-blocks' );
			global $bppblocks_plugin_dir_path;

			$msg_success  = array();
			$msg_error    = array();
			$flycart_po   = $bppblocks_plugin_dir_path . 'languages/woofc-ja.po';
			$flycart_mo   = $bppblocks_plugin_dir_path . 'languages/woofc-ja.mo';
			$plugins_path = trailingslashit( dirname( $bppblocks_plugin_dir_path ) );

			foreach ( $_POST[ 'bpb' ] as $key => $v ) {
				switch ( $key ) {
					case 'wp_fly_cart_trans_plugin':
						$woo_fly_cart_dir = $plugins_path . 'woo-fly-cart/languages/';
						if ( file_exists( $woo_fly_cart_dir ) ) {
							copy( $flycart_po, $woo_fly_cart_dir . basename( $flycart_po ) );
							copy( $flycart_mo, $woo_fly_cart_dir . basename( $flycart_mo ) );
							$msg_success[] = 'Woo Fly Cartプラグイン内にファイルをコピーしました。';
						} else {
							$msg_error[] = $woo_fly_cart_dir . ' が存在しないため、コピーしませんでした。';
						}
						break;

					case 'wp_fly_cart_trans_system':
						$lang_dir = WP_CONTENT_DIR . '/languages/plugins/';
						if ( file_exists( $lang_dir ) ) {
							copy( $flycart_po, $lang_dir . basename( $flycart_po ) );
							copy( $flycart_mo, $lang_dir . basename( $flycart_mo ) );
							$msg_success[] = 'WordPressシステム内にファイルをコピーしました。';
						} else {
							$msg_error[] = $lang_dir . ' が存在しないため、コピーしませんでした。';
						}
						break;

					case 'wp_fly_cart_trans_loco':
						$lang_dir = WP_CONTENT_DIR . '/languages/loco/plugins/';
						if ( file_exists( $lang_dir ) ) {
							copy( $flycart_po, $lang_dir . basename( $flycart_po ) );
							copy( $flycart_mo, $lang_dir . basename( $flycart_mo ) );
							$msg_success[] = 'Locoプラグイン用ディレクトリ内にファイルをコピーしました。';
						} else {
							$msg_error[] = $lang_dir . ' が存在しないため、コピーしませんでした。';
						}
						break;
				}
			}

			if ( count( $msg_success ) ) {
				echo '<div class="notice notice-success is-dismissible"><p>';
				foreach ( $msg_success as $l ) {
					echo $l . '<br>';
				}
				echo '</p></div>';
			}

			if ( count( $msg_error ) ) {
				echo '<div class="notice notice-error is-dismissible"><p>';
				foreach ( $msg_error as $l ) {
					echo $l . '<br>';
				}
				echo '</p></div>';
			}
		}
		?>

		<div class="wrap">
			<h2>bp-blocks</h2>
			<p>bp-blocks関連の設定を行えます。実行したい処理を選んで、実行ボタンを押してください。</p>

			<form method="post" action="<?php admin_url( 'options-general.php?page=bp-blocks' ); ?>">
			<table class="form-table">
				<tbody>
				<tr>
					<th scope="row">
						<label for="my-text-field">WP Fly Cartの翻訳ファイルを更新</label>
					</th>
					<td>
						<label><input type="checkbox" name="bpb[wp_fly_cart_trans_plugin]" value="true" /> プラグインの言語ファイルを更新する</label><br>
						<label><input type="checkbox" name="bpb[wp_fly_cart_trans_system]" value="true" /> システムの言語ファイルを更新する</label><br>
						<label><input type="checkbox" name="bpb[wp_fly_cart_trans_loco]" value="true" /> Loco翻訳プラグインの言語ファイルを更新する</label><br>
						<br>
						<span class="description">翻訳ファイルを上書き保存します。必要な設定を行った上で、実行してください（特にLoco Translateプラグイン）</span>
					</td>
				</tr>
				</tbody>
			</table>
			<?php wp_nonce_field( 'bp-blocks' ); ?>
			<input type="hidden" name="cmd" value="bp-blocks-settings" />
			<?php submit_button( '実行' ); ?>
		</form>
		</div>
	<?php }

	public function bp_blocks_page_init() {

	}

	public function bp_blocks_section_info() {
		
	}
}
if ( is_admin() )
	$bp_blocks = new BpBlocks();

/* 
 * Retrieve this value with:
 * $bp_blocks_options = get_option( 'bp_blocks_option_name' ); // Array of All Options
 * $wp_fly_cart__0 = $bp_blocks_options['wp_fly_cart__0']; // WP Fly Cart 日本語ファイルをコピーする
 */