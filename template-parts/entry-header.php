<?php
/**
 * Displays the post header
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

$entry_header_classes = '';

if ( is_singular() ) {
	$entry_header_classes .= ' header-footer-group';
}

?>

<header class="entry-header has-text-align-center<?php echo esc_attr( $entry_header_classes ); ?>">

	<div class="entry-header-inner section-inner medium">

		<?php

		if ( is_singular() ) {
			if ( is_page() ) {
				// Si c'est une page 
			}else{
				the_title( '<h1 class="entry-title">', '</h1>' );
			}
			
		} else {
			//the_title( '<h2 class="entry-title heading-size-1"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' );
		}


		?>

	</div><!-- .entry-header-inner -->

</header><!-- .entry-header -->
