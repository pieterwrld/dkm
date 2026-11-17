<?php
/**
 * My Account dashboard
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package DKM
 * @version 4.4.0
 *
 * @var WP_User $current_user
 */

defined( 'ABSPATH' ) || exit;

$allowed_html = array(
	'a' => array( 'href' => array() ),
);
?>

<div class="acc__hello">
	<span class="eyebrow"><?php esc_html_e( 'Jouw account', 'dkm' ); ?></span>
	<h1 class="h-m">
		<?php
		/* translators: %s is the customer's display name. */
		printf( esc_html__( 'Hoi %s', 'dkm' ), esc_html( $current_user->display_name ) );
		?>
	</h1>
	<p class="lead">
		<?php esc_html_e( 'Hier zie je je bestellingen, je adres en je gegevens.', 'dkm' ); ?>
		<a href="<?php echo esc_url( wc_logout_url() ); ?>"><?php esc_html_e( 'Uitloggen', 'dkm' ); ?></a>
	</p>
</div>

<div class="acc__tiles">
	<a class="acc__tile" href="<?php echo esc_url( wc_get_endpoint_url( 'orders' ) ); ?>">
		<?php dkm_the_icon( 'box' ); ?>
		<b><?php esc_html_e( 'Bestellingen', 'dkm' ); ?></b>
		<span><?php esc_html_e( 'Bekijk wat je hebt besteld en volg je pakket.', 'dkm' ); ?></span>
	</a>
	<a class="acc__tile" href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address' ) ); ?>">
		<?php dkm_the_icon( 'truck' ); ?>
		<b><?php esc_html_e( 'Adressen', 'dkm' ); ?></b>
		<span><?php esc_html_e( 'Pas je bezorg- en factuuradres aan.', 'dkm' ); ?></span>
	</a>
	<a class="acc__tile" href="<?php echo esc_url( wc_get_endpoint_url( 'edit-account' ) ); ?>">
		<?php dkm_the_icon( 'shield' ); ?>
		<b><?php esc_html_e( 'Gegevens', 'dkm' ); ?></b>
		<span><?php esc_html_e( 'Wijzig je naam, e-mailadres of wachtwoord.', 'dkm' ); ?></span>
	</a>
</div>

<?php
/** This hook is documented in woocommerce/templates/myaccount/dashboard.php */
do_action( 'woocommerce_account_dashboard' );

/** @deprecated 2.6.0 */
do_action( 'woocommerce_before_my_account' );

/** @deprecated 2.6.0 */
do_action( 'woocommerce_after_my_account' );
