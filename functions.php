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

	
	<!-- Start Open Web Analytics Tracker -->
	<script type="text/javascript">
	//<![CDATA[
	var owa_baseUrl = 'https://stats.atelier-contigo.fr/';
	var owa_cmds = owa_cmds || [];
	owa_cmds.push(['setSiteId', 'bb56d45ff811c76d3e0236a66f85d502']);
	owa_cmds.push(['trackPageView']);
	owa_cmds.push(['trackClicks']);

	(function() {
		var _owa = document.createElement('script'); _owa.type = 'text/javascript'; _owa.async = true;
		owa_baseUrl = ('https:' == document.location.protocol ? window.owa_baseSecUrl || owa_baseUrl.replace(/http:/, 'https:') : owa_baseUrl );
		_owa.src = owa_baseUrl + 'modules/base/dist/owa.tracker.js';
		var _owa_s = document.getElementsByTagName('script')[0]; _owa_s.parentNode.insertBefore(_owa, _owa_s);
	}());
	//]]>
	</script>
	<!-- End Open Web Analytics Code -->

        

<?php } );

// Ancre formulaire
add_filter( 'gform_confirmation_anchor', '__return_true' );


// Performance
// Source = https://kinsta.com/knowledgebase/disable-emojis-wordpress/#disable-emojis-code

/**
 * Disable the emoji's
 */
function disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' ); 
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' ); 
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
    add_filter( 'wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2 );
   }
   add_action( 'init', 'disable_emojis' );
   
   /**
    * Filter function used to remove the tinymce emoji plugin.
    * 
    * @param array $plugins 
    * @return array Difference betwen the two arrays
    */
   function disable_emojis_tinymce( $plugins ) {
    if ( is_array( $plugins ) ) {
    return array_diff( $plugins, array( 'wpemoji' ) );
    } else {
    return array();
    }
   }
   
   /**
    * Remove emoji CDN hostname from DNS prefetching hints.
    *
    * @param array $urls URLs to print for resource hints.
    * @param string $relation_type The relation type the URLs are printed for.
    * @return array Difference betwen the two arrays.
    */
   function disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
    if ( 'dns-prefetch' == $relation_type ) {
    /** This filter is documented in wp-includes/formatting.php */
    $emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );
   
   $urls = array_diff( $urls, array( $emoji_svg_url ) );
    }
   
   return $urls;
   }