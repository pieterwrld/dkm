<?php
/**
 * Footer, cart drawer, floating WhatsApp button and the toast
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

$email    = (string) get_theme_mod( 'dkm_email', 'info@deklokjesman.com' );
$snapchat = (string) get_theme_mod( 'dkm_snapchat', '' );
$sister   = (string) get_theme_mod( 'dkm_sister_url', 'https://deoutfitman.com' );
$sister_n = (string) get_theme_mod( 'dkm_sister_name', 'De Outfitman' );
$wa_text  = (string) get_theme_mod( 'dkm_whatsapp_text', '' );
$wa       = dkm_whatsapp_url( $wa_text );
$phone    = (string) get_theme_mod( 'dkm_whatsapp', '31647974408' );
?>
</main>

<footer class="ft">
	<div class="ft__glow" aria-hidden="true"></div>
	<div class="ft__lines" aria-hidden="true"></div>

	<div class="wrap">
		<div class="ft__cta">
			<div>
				<h3><?php esc_html_e( 'Twijfel je tussen', 'dkm' ); ?> <span class="em gr-txt"><?php esc_html_e( 'twee modellen?', 'dkm' ); ?></span></h3>
				<p><?php esc_html_e( 'Stuur ons een bericht. We denken met je mee, sturen extra foto’s en vertellen eerlijk welke kwaliteit bij je past.', 'dkm' ); ?></p>
			</div>
			<div class="ft__acts">
				<?php if ( $wa ) : ?>
					<a class="btn btn--g btn--lg" href="<?php echo esc_url( $wa ); ?>" target="_blank" rel="noopener">
						<?php dkm_the_icon( 'wa' ); ?><?php esc_html_e( 'Chat via WhatsApp', 'dkm' ); ?>
					</a>
				<?php endif; ?>
				<a class="btn btn--gh btn--lg" href="<?php echo esc_url( dkm_info_page_url() ); ?>"><?php esc_html_e( 'Kwaliteitsinfo', 'dkm' ); ?></a>
			</div>
		</div>

		<div class="ft__usp">
			<?php foreach ( dkm_usps() as [$icon, $title, $sub] ) : ?>
				<div class="ft__u">
					<?php dkm_the_icon( $icon ); ?>
					<div>
						<b><?php echo esc_html( $title ); ?></b>
						<span><?php echo esc_html( $sub ); ?></span>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="ft__g">
			<div>
				<?php echo dkm_logo( array( 'light' => true, 'size' => '22px' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<p><?php esc_html_e( 'Elegant. Exclusief. Tijdloos. Luxe horloges in drie kwaliteitsniveaus, met persoonlijk advies via WhatsApp.', 'dkm' ); ?></p>
				<div class="soc">
					<?php if ( $wa ) : ?>
						<a href="<?php echo esc_url( $wa ); ?>" target="_blank" rel="noopener"><?php dkm_the_icon( 'wa', '', __( 'WhatsApp', 'dkm' ) ); ?></a>
					<?php endif; ?>
					<?php if ( $snapchat ) : ?>
						<a href="<?php echo esc_url( $snapchat ); ?>" target="_blank" rel="noopener"><?php dkm_the_icon( 'snap', '', __( 'Snapchat', 'dkm' ) ); ?></a>
					<?php endif; ?>
					<?php if ( $email ) : ?>
						<a href="<?php echo esc_url( 'mailto:' . $email ); ?>"><?php dkm_the_icon( 'mail', '', __( 'E-mail', 'dkm' ) ); ?></a>
					<?php endif; ?>
				</div>
			</div>

			<div>
				<h5><?php esc_html_e( 'Shop', 'dkm' ); ?></h5>
				<?php if ( has_nav_menu( 'footer-shop' ) ) : ?>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer-shop',
							'container'      => false,
							'items_wrap'     => '%3$s',
							'link_before'    => '',
							'depth'          => 1,
							'walker'         => new DKM_Footer_Walker(),
						)
					);
					?>
				<?php else : ?>
					<a class="ft__l" href="<?php echo esc_url( dkm_shop_url() ); ?>"><?php esc_html_e( 'Alle horloges', 'dkm' ); ?></a>
					<?php foreach ( dkm_nav_items() as $item ) : ?>
						<a class="ft__l" href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>

			<div>
				<h5><?php esc_html_e( 'Informatie', 'dkm' ); ?></h5>
				<?php if ( has_nav_menu( 'footer-info' ) ) : ?>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer-info',
							'container'      => false,
							'items_wrap'     => '%3$s',
							'depth'          => 1,
							'walker'         => new DKM_Footer_Walker(),
						)
					);
					?>
				<?php else : ?>
					<a class="ft__l" href="<?php echo esc_url( dkm_info_page_url() ); ?>"><?php esc_html_e( 'Kwaliteitsinformatie', 'dkm' ); ?></a>
				<?php endif; ?>
			</div>

			<div>
				<h5><?php esc_html_e( 'Direct contact', 'dkm' ); ?></h5>
				<?php if ( $wa ) : ?>
					<a class="ft__l" href="<?php echo esc_url( $wa ); ?>" target="_blank" rel="noopener">
						<?php dkm_the_icon( 'wa' ); ?><?php echo esc_html( dkm_format_phone( $phone ) ); ?>
					</a>
				<?php endif; ?>
				<?php if ( $email ) : ?>
					<a class="ft__l" href="<?php echo esc_url( 'mailto:' . $email ); ?>">
						<?php dkm_the_icon( 'mail' ); ?><?php echo esc_html( $email ); ?>
					</a>
				<?php endif; ?>
				<?php if ( $sister ) : ?>
					<a class="ft__l" href="<?php echo esc_url( $sister ); ?>" target="_blank" rel="noopener">
						<?php dkm_the_icon( 'arrow' ); ?>
						<?php
						/* translators: %s is the name of the sister brand. */
						echo esc_html( $sister_n );
						?>
					</a>
				<?php endif; ?>
			</div>
		</div>

		<div class="ft__bot">
			<span>
				<?php
				/* translators: %1$s is the year, %2$s the site name. */
				printf( esc_html__( '© %1$s %2$s. Alle rechten voorbehouden.', 'dkm' ), esc_html( gmdate( 'Y' ) ), esc_html( get_bloginfo( 'name' ) ) );
				?>
			</span>
			<span><?php esc_html_e( 'Vandaag besteld = morgen in huis', 'dkm' ); ?></span>
		</div>
	</div>
</footer>

<?php
get_template_part( 'template-parts/cart-drawer' );
get_template_part( 'template-parts/whatsapp-button' );
?>

<div class="modal" id="dkm-modal" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Meer informatie', 'dkm' ); ?>" hidden>
	<div class="modal__bd" data-dkm-close="modal"></div>
	<div class="modal__c">
		<button type="button" class="modal__x" data-dkm-close="modal">
			<?php dkm_the_icon( 'close', '', __( 'Sluiten', 'dkm' ) ); ?>
		</button>
		<div class="modal__hd" id="dkm-modal-head"></div>
		<div class="modal__b" id="dkm-modal-body"></div>
	</div>
</div>

<div class="toast" id="dkm-toast" role="status" aria-live="polite"></div>

<?php wp_footer(); ?>
</body>
</html>
