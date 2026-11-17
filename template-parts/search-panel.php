<?php
/**
 * Search panel that drops out of the header.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="searchp"
	id="search-panel"
	role="dialog"
	aria-modal="true"
	aria-label="<?php esc_attr_e( 'Zoeken', 'dkm' ); ?>"
	hidden>
	<div class="wrap searchp__in">
		<form role="search" method="get" class="searchp__form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label class="screen-reader-text" for="dkm-search-field"><?php esc_html_e( 'Zoek een horloge', 'dkm' ); ?></label>
			<?php dkm_the_icon( 'search', 'searchp__ico' ); ?>
			<input
				type="search"
				id="dkm-search-field"
				class="searchp__input"
				name="s"
				value="<?php echo esc_attr( get_search_query() ); ?>"
				placeholder="<?php esc_attr_e( 'Zoek op model, kleur of kwaliteit', 'dkm' ); ?>"
				autocomplete="off">
			<input type="hidden" name="post_type" value="product">
			<button type="submit" class="btn btn--g searchp__submit"><?php esc_html_e( 'Zoek', 'dkm' ); ?></button>
			<button type="button" class="iconbtn searchp__close" data-dkm-close="search">
				<?php dkm_the_icon( 'close', '', __( 'Zoeken sluiten', 'dkm' ) ); ?>
			</button>
		</form>

		<div class="searchp__quick">
			<span class="searchp__quick-label"><?php esc_html_e( 'Populair', 'dkm' ); ?></span>
			<?php
			$quick = array( 'Submariner', 'Daytona', 'Datejust', 'GMT-Master', 'Day-Date' );
			foreach ( $quick as $term ) :
				$url = add_query_arg(
					array(
						's'         => $term,
						'post_type' => 'product',
					),
					home_url( '/' )
				);
				?>
				<a class="chs" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $term ); ?></a>
			<?php endforeach; ?>
		</div>
	</div>
</div>
