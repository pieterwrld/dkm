<?php
/**
 * Login and register
 *
 * Two columns that stack on a phone. The field markup and every hook are kept
 * as WooCommerce prints them, so registration plugins keep working.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_customer_login_form' );
?>

<section class="sec">
	<div class="wrap">
		<div class="sec-hd">
			<span class="eyebrow"><?php esc_html_e( 'Account', 'dkm' ); ?></span>
			<h1 class="h-l">
				<?php esc_html_e( 'Inloggen of', 'dkm' ); ?>
				<span class="em gr-txt"><?php esc_html_e( 'account aanmaken', 'dkm' ); ?></span>
			</h1>
		</div>

		<div class="login-split<?php echo ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) ? '' : ' login-split--single'; ?>">
			<div class="u-column1">
				<h2><?php esc_html_e( 'Inloggen', 'dkm' ); ?></h2>
				<p class="lead"><?php esc_html_e( 'Bekijk je bestellingen en volg je verzending.', 'dkm' ); ?></p>

				<form class="woocommerce-form woocommerce-form-login login" method="post">

					<?php do_action( 'woocommerce_login_form_start' ); ?>

					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="username"><?php esc_html_e( 'E-mailadres', 'dkm' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
						<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required>
					</p>

					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="password"><?php esc_html_e( 'Wachtwoord', 'dkm' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
						<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" required>
					</p>

					<?php do_action( 'woocommerce_login_form' ); ?>

					<p class="form-row">
						<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
							<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" value="forever">
							<span><?php esc_html_e( 'Ingelogd blijven', 'dkm' ); ?></span>
						</label>
						<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
						<button type="submit" class="btn btn--g btn--full btn--lg woocommerce-button button woocommerce-form-login__submit" name="login" value="<?php esc_attr_e( 'Inloggen', 'dkm' ); ?>">
							<?php esc_html_e( 'Inloggen', 'dkm' ); ?>
						</button>
					</p>

					<p class="woocommerce-LostPassword lost_password">
						<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Wachtwoord vergeten?', 'dkm' ); ?></a>
					</p>

					<?php do_action( 'woocommerce_login_form_end' ); ?>
				</form>
			</div>

			<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>
				<div class="u-column2">
					<h2><?php esc_html_e( 'Account aanmaken', 'dkm' ); ?></h2>
					<p class="lead"><?php esc_html_e( 'Zo hoef je je gegevens maar één keer in te vullen.', 'dkm' ); ?></p>

					<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?>>

						<?php do_action( 'woocommerce_register_form_start' ); ?>

						<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
							<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
								<label for="reg_username"><?php esc_html_e( 'Gebruikersnaam', 'dkm' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
								<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required>
							</p>
						<?php endif; ?>

						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label for="reg_email"><?php esc_html_e( 'E-mailadres', 'dkm' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
							<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" required>
						</p>

						<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
							<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
								<label for="reg_password"><?php esc_html_e( 'Wachtwoord', 'dkm' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
								<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" required>
							</p>
						<?php else : ?>
							<p class="lead"><?php esc_html_e( 'Je ontvangt een wachtwoord per e-mail.', 'dkm' ); ?></p>
						<?php endif; ?>

						<?php do_action( 'woocommerce_register_form' ); ?>

						<p class="woocommerce-form-row form-row">
							<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
							<button type="submit" class="btn btn--o btn--full btn--lg woocommerce-Button woocommerce-button button woocommerce-form-register__submit" name="register" value="<?php esc_attr_e( 'Account aanmaken', 'dkm' ); ?>">
								<?php esc_html_e( 'Account aanmaken', 'dkm' ); ?>
							</button>
						</p>

						<?php do_action( 'woocommerce_register_form_end' ); ?>
					</form>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
