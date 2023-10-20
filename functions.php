<?php
// Connexion au theme parent
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
    $parenthandle = 'twentytwenty-style'; // This is 'twentyfifteen-style' for the Twenty Fifteen theme.
    $theme = wp_get_theme();

    wp_enqueue_style( $parenthandle, get_template_directory_uri() . '/style.css', 
        array(),  // if the parent theme code has a dependency, copy it to here
        $theme->parent()->get('Version')
    );
    
    wp_enqueue_style( 'child-style', get_stylesheet_uri(),
        array( $parenthandle ),
        $theme->get('Version') // this only works if you have Version in the style header
    );
}

// Personnalisation du back office : Ajout d'une colonne pour l'image à la une

// This action hook allows to add a new empty column
add_filter( 'manage_toile_posts_columns', 'rudr_featured_image_column' );
function rudr_featured_image_column( $cols ) {

	$cols = array_slice( $cols, 0, 1, true )
	+ array( 'featured_image' => 'Featured Image' ) // our new column
	+ array_slice( $cols, 1, NULL, true );

	return $cols;
}

// This hook fills our column with data
add_action( 'manage_posts_custom_column', 'rudr_render_the_column', 10, 2 );
function rudr_render_the_column( $column_name, $post_id ) {

	if( 'featured_image' === $column_name ) {

		// if there is no featured image for this post, print the placeholder
		if( has_post_thumbnail( $post_id ) ) {

			// I know about get_the_post_thumbnail() function but we need data-id attribute here
			$id = get_post_thumbnail_id( $post_id );
			$url = esc_url( wp_get_attachment_image_url( $id ) );
			?><img data-id="<?php echo $id ?>" src="<?php echo $url ?>" /><?php

		} else {
			// data-id should be "-1" I will explain below
			?><img data-id="-1" src="https://via.placeholder.com/100" /><?php
		}
	}
}

// Mise en place de style CSS pour le backoffice
add_action('admin_head', 'my_custom_css2backOffice');

function my_custom_css2backOffice() {
  echo '<style>
  #featured_image{
        width:120px;
    }
    td.featured_image.column-featured_image img{
        max-width: 100%;
        height: auto;
    }
    .editor-styles-wrapper p{
        font-family: "Inter var", -apple-system, BlinkMacSystemFont, "Helvetica Neue", Helvetica, sans-serif;
    }
    .wp-block {
        max-width: 90%;
      }
  </style>';
}


// Ajout de scripts dans le header
add_action( 'wp_head', function() { ?>

    <!-- Atlas Icons -->
    <link rel='stylesheet' href='/wp-content/themes/coupille/lib/Atlas-icons-font-main/style.css' media='all' />      

<?php } );

// Ancre formulaire
//add_filter( 'gform_confirmation_anchor', '__return_true' );
add_filter( 'gform_confirmation_anchor', function() {
    return 20;
} );

// Ajouter le champ CC dans les formulaires
add_filter('gform_notification_enable_cc', 'enable_cc', 10, 3 );
 
function enable_cc( $enable, $notification, $form ){
  return true;
}