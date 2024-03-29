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
if ( is_page() ) {
	// Si c'est une page 
}else{
?>

<header class="entry-header has-text-align-center<?php echo esc_attr( $entry_header_classes ); ?>">

	<div class="entry-header-inner section-inner medium">

		<?php
			the_title( '<h1 class="entry-title">', '</h1>' );
		?>

	</div><!-- .entry-header-inner -->

</header><!-- .entry-header -->

<?php

}