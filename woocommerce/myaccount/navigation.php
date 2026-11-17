<?php
/**
 * My Account navigation
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package DKM
 * @version 9.3.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_navigation' );

$dkm_icons = array(
	'dashboard'       => 'watch',
	'orders'          => 'box',
	'downloads'       => 'tag',
	'edit-address'    => 'truck',
	'payment-methods' => 'lock',
	'edit-account'    => 'shield',
	'customer-logout' => 'close',
);
?>

<nav class="woocommerce-MyAccount-navigation acc__nav" aria-label="<?php esc_attr_e( 'Accountpagina’s', 'dkm' ); ?>">
	<ul>
		<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
			<li class="<?php echo esc_attr( wc_get_account_menu_item_classes( $endpoint ) ); ?>">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" <?php echo wc_is_current_account_menu_item( $endpoint ) ? 'aria-current="page"' : ''; ?>>
					<?php dkm_the_icon( $dkm_icons[ $endpoint ] ?? 'arrow' ); ?>
					<span><?php echo esc_html( $label ); ?></span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>
