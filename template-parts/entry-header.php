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

<?php
// Fonction personnalisée pour afficher le fil d'Ariane
function custom_breadcrumbs() {
    echo '<nav class="breadcrumb">';
    echo '<a href="' . home_url() . '">Accueil</a> » ';
    
    if (is_single()) {
        // $category = get_the_category();
        // if (!empty($category)) {
        //     echo '<a href="' . get_category_link($category[0]->term_id) . '">' . $category[0]->name . '</a> » ';
        // }
		echo '<a href="https://www.coupille.fr/journal/">Journal</a> » ';
        echo '<span>' . get_the_title() . '</span>';
    }

    echo '</nav>';
}

// Afficher le fil d'Ariane
custom_breadcrumbs();
?>

	</div><!-- .entry-header-inner -->

</header><!-- .entry-header -->

<?php

}