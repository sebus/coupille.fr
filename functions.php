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

    <script src="https://kit.fontawesome.com/13e086d506.js" crossorigin="anonymous"></script>    

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

//

// Ajouter une colonne pour un champ personnalisé ACF dans le tableau d'administration du CPT 'toile'
add_filter('manage_toile_posts_columns', 'ajouter_colonnes_personnalisees_toile');
function ajouter_colonnes_personnalisees_toile($columns) {
    // Ajouter une nouvelle colonne après le titre
    $columns['titre'] = __('Titre affiché', 'twentytwentychild');
    $columns['num_toile'] = __('Numéro de Toile', 'twentytwentychild');
    $columns['annee'] = __('Année', 'twentytwentychild');
    $columns['localisation'] = __('Localisation', 'twentytwentychild');
    return $columns;
}

// Remplir les colonnes avec les valeurs des champs ACF
add_action('manage_toile_posts_custom_column', 'remplir_colonnes_personnalisees_toile', 10, 2);
function remplir_colonnes_personnalisees_toile($column, $post_id) {
    switch ($column) {
        case 'titre':
            // Récupérer et afficher la valeur du champ ACF 'titre'
            $titre = get_field('titre', $post_id);
            echo esc_html($titre);
            break;
        case 'num_toile':
            // Récupérer et afficher la valeur du champ ACF 'num_toile'
            $num_toile = get_field('num_toile', $post_id);
            echo esc_html($num_toile);
            break;
        case 'annee':
            // Récupérer et afficher la valeur du champ ACF 'annee'
            $annee = get_field('annee', $post_id);
            echo esc_html($annee);
            break;
        case 'localisation':
            // Récupérer et afficher la valeur du champ ACF 'annee'
            $localisation = get_field('localisation', $post_id);
            echo esc_html($localisation);
            break;
    }
}
// Rendre les colonnes triables
add_filter('manage_edit-toile_sortable_columns', 'rendre_colonnes_toile_triables');
function rendre_colonnes_toile_triables($sortable_columns) {
    $sortable_columns['num_toile'] = 'num_toile';
    $sortable_columns['annee'] = 'annee';
    $sortable_columns['localisation'] = 'localisation';
    return $sortable_columns;
}

// Adapter la requête pour que le tri fonctionne
add_action('pre_get_posts', 'adapter_tri_toile_colonnes');
function adapter_tri_toile_colonnes($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    $orderby = $query->get('orderby');

    if ('num_toile' === $orderby) {
        $query->set('meta_key', 'num_toile');
        $query->set('orderby', 'meta_value_num');
    }

    if ('annee' === $orderby) {
        $query->set('meta_key', 'annee');
        $query->set('orderby', 'meta_value_num');
    }
}

// Ajouter un filtre pour le champ ACF "localisation"
function filtre_acf_localisation() {
    global $typenow;

    if ($typenow == 'toile') { // Vérifier si on est dans le bon post type

        // Requête pour récupérer les valeurs uniques du champ ACF 'localisation'
        global $wpdb;
        $results = $wpdb->get_col("
            SELECT DISTINCT meta_value 
            FROM $wpdb->postmeta 
            WHERE meta_key = 'localisation'
            AND meta_value != ''
        ");

        if (!empty($results)) {
            echo '<select name="localisation">';
            echo '<option value="">Toutes les localisations</option>';

            foreach ($results as $localisation) {
                // Tableau associatif pour les localisations
                $localisations = [
                    'nan' => 'Non renseigné',
                    'chateau' => 'Château de Charleval',
                    'box' => 'Box Lauris',
                    'sam' => 'Samuel K',
                    'remi' => 'Rémi C',
                    'paul' => 'Paul C',
                    'hangar' => 'Le petit musée'
                ];

                // Utilisation d'une valeur par défaut si la localisation n'est pas dans le tableau
                $localisation_clean = isset($localisations[$localisation]) ? $localisations[$localisation] : 'Localisation inconnue';

                $selected = (isset($_GET['localisation']) && $_GET['localisation'] == $localisation) ? 'selected="selected"' : '';
                echo '<option value="' . esc_attr($localisation) . '" ' . $selected . '>' . esc_html($localisation_clean) . '</option>';

                $localisation_clean='';
            }

            echo '</select>';
        }
    }
}
//add_action('restrict_manage_posts', 'filtre_acf_localisation');



