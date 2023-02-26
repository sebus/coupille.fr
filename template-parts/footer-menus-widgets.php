<?php
/**
 * Displays the menus and widgets at the end of the main element.
 * Visually, this output is presented as part of the footer element.
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

$has_footer_menu = has_nav_menu( 'footer' );
$has_social_menu = has_nav_menu( 'social' );

$has_sidebar_1 = is_active_sidebar( 'sidebar-1' );
$has_sidebar_2 = is_active_sidebar( 'sidebar-2' );

?>
<footer id="site-footer" class="header-footer-group">

<div class="section-inner">

	<div class="footer-credits">

	<div class="footer-menu-nav">
		<?php if ( $has_sidebar_1 ) { ?>

		<?php dynamic_sidebar( 'sidebar-1' ); ?>

		<?php } ?>
	</div>

	</div><!-- .footer-credits -->

	<a class="to-the-top" href="#site-header">
		<span class="to-the-top-long">
			<?php
			/* translators: %s: HTML character for up arrow. */
			printf( __( 'To the top %s', 'twentytwenty' ), '<span class="arrow" aria-hidden="true">&uarr;</span>' );
			?>
		</span><!-- .to-the-top-long -->
		<span class="to-the-top-short">
			<?php
			/* translators: %s: HTML character for up arrow. */
			printf( __( 'Up %s', 'twentytwenty' ), '<span class="arrow" aria-hidden="true">&uarr;</span>' );
			?>
		</span><!-- .to-the-top-short -->
	</a><!-- .to-the-top -->

</div><!-- .section-inner -->

</footer><!-- #site-footer -->
<?php








// Only output the container if there are elements to display.
if ( $has_footer_menu || $has_social_menu || $has_sidebar_2 ) {
	?>

	<div class="footer-nav-widgets-wrapper header-footer-group">

		<div class="footer-inner section-inner">

			<?php if ( $has_sidebar_2 ) { ?>

				<aside class="footer-widgets-outer-wrapper">


						<?php if ( $has_sidebar_2 ) { ?>

								<?php dynamic_sidebar( 'sidebar-2' ); ?>

						<?php } ?>


				</aside><!-- .footer-widgets-outer-wrapper -->

			<?php } ?>

		</div><!-- .footer-inner -->

	</div><!-- .footer-nav-widgets-wrapper -->

<?php } ?>
