<?php
/**
 * My Account page
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package DKM
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="acc">
	<?php
	/** This hook is documented in woocommerce/templates/myaccount/my-account.php */
	do_action( 'woocommerce_account_navigation' );
	?>

	<div class="woocommerce-MyAccount-content acc__content">
		<?php
		/** This hook is documented in woocommerce/templates/myaccount/my-account.php */
		do_action( 'woocommerce_account_content' );
		?>
	</div>
</div>
