<?php
/**
 * WooCommerce のために、モーダルログインウィンドウや、ログアウトリンクを生成する
 */
add_action(
	'wp_enqueue_scripts',
	function() {
		global $bpb_plugin_file_path;

		wp_enqueue_style( 'light-modal', plugins_url( 'css/light-modal.css', $bpb_plugin_file_path ), false, '0.8.3', 'all' );
		wp_enqueue_style( 'animate', plugins_url( 'css/animate.css', $bpb_plugin_file_path ), false, '0.8.3', 'all' );
	}
);

add_action(
	'wp_footer',
	function () {
		if ( is_user_logged_in() ) {
			return;
		}
		?>
<div class="light-modal" id="bp-login-form" role="dialog" aria-labelledby="light-modal-label" aria-hidden="false">
	<div class="light-modal-content animate__animated animate__fadeInDown">
		<!-- light modal header -->
		<div class="light-modal-header">
			<h3 class="light-modal-heading">会員ログイン</h3>
			<a href="#" class="light-modal-close-icon" aria-label="close">&times;</a>
		</div>
		<!-- light modal body -->
		<div class="light-modal-body">
			<!-- Your content -->
			<?php echo bpb_get_wc_login_form(); ?>
		</div>
	</div>
</div>
		<?php
	}
);
if ( ! function_exists( 'bpb_get_wc_login_form' ) ) {

	function bpb_get_wc_login_form( $redirect_url = null ) {

		if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			return '<p>WooCommerceプラグインを有効化してください</p>';
		}

		if ( $redirect_url == null ) {
			$redirect_url = ( is_ssl() ? 'https' : 'http' ) . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		}

		// error message がある場合、モーダルウィンドウを表示する（ための準備）
		ob_start();
		wc_print_notices();
		$wc_notices = ob_get_contents();
		ob_end_clean();

		// ログインフォームの取得
		ob_start();
		echo $wc_notices;
		woocommerce_login_form( array( 'redirect' => $redirect_url ) );
		$login_form = ob_get_contents();
		ob_end_clean();

		// 登録フォームの取得
		ob_start();
		?>
	<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>
		<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

		<?php do_action( 'woocommerce_register_form_start' ); ?>

		<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="reg_username"><?php esc_html_e( 'Username', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
			</p>

		<?php endif; ?>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="reg_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
			<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
		</p>

		<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="reg_password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
				<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" />
			</p>

		<?php else : ?>

			<p><?php esc_html_e( 'A password will be sent to your email address.', 'woocommerce' ); ?></p>

		<?php endif; ?>

		<?php do_action( 'woocommerce_register_form' ); ?>

		<p class="woocommerce-form-row form-row">
			<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
			<button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php esc_html_e( 'Register', 'woocommerce' ); ?></button>
		</p>

		<?php do_action( 'woocommerce_register_form_end' ); ?>

		</form>
	<?php else : ?>
		<p>ここで、ユーザー登録はできません</p>
	<?php endif; ?>
		<?php
		$register_form = ob_get_contents();
		ob_end_clean();   // 登録フォーム取得、ここまで

		$html = <<<EOD
	<div class="tabs">
		<input id="wc-login" type="radio" name="tab_item" checked>
		<label class="tab_item" for="wc-login">会員ログイン</label>
		<input id="wc-register" type="radio" name="tab_item">
		<label class="tab_item" for="wc-register">新規登録</label>
		<div class="tab_content" id="wc-login_content">
			{$login_form}
		</div>
		<div class="tab_content" id="wc-register_content">
			{$register_form}
		</div>
	</div>
EOD;

		return $html;
	}
}


add_action(
	'wp_footer',
	function () {
		if ( ! is_user_logged_in() ) {
			return;
		}

		$redirect_url = ( is_ssl() ? 'https' : 'http' ) . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		?>
<div class="light-modal" id="bp-logout-form" role="dialog" aria-labelledby="light-modal-label" aria-hidden="false">
	<div class="light-modal-content animate__animated animate__fadeInDown">
		<!-- light modal header -->
		<div class="light-modal-header">
			<h3 class="light-modal-heading">ログアウト確認</h3>
			<a href="#" class="light-modal-close-icon" aria-label="close">&times;</a>
		</div>
		<!-- light modal body -->
		<div class="light-modal-body">
			<!-- Your content -->
			<p>本当に、ログアウトしますか？</p>
			<p><button onclick="location.href='<?php echo wp_logout_url( $redirect_url ); ?>'">はい、ログアウトします</button>				
			<br><br>
			<a href="#">いいえ、ログアウトしません</a>
			</p>
		</div>
	</div>
</div>
		<?php
	}
);