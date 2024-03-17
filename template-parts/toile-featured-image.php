<?php
/**
 * Displays the featured image
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */



if ( has_post_thumbnail() ) {

	?>
	<div class="toile-container">
		<div class="toile-notice">
			<div class="sticky">

				<div class="navPost">
				

				<?php
				// On récupère l'ID de la publication courante
				$current_post_id = get_the_ID();

				// On récupère le numéro de la toile
				$num_toile = get_field('num_toile');
				//echo $num_toile.'-';

				// Construction des paramètres personnalisés pour la requête précédente
				$prev_post_args = array(
					'post_type'     => 'toile', // type du post
					'meta_key' => 'num_toile', // Key pour le champ ACF qui va servir de tri
					'meta_value' => $num_toile, // Valeur
					'meta_compare' => '<', // Opérateur
					
					'posts_per_page' => 1
				);
				
				// Requête personnalisée
				$prev_post_query = new WP_Query($prev_post_args);
				
				// Boucle
				if ($prev_post_query->have_posts()):

					while( $prev_post_query->have_posts() ) : $prev_post_query->the_post();

					$prev_post_id = get_the_ID();
					$num_toile_prec = get_field('num_toile');
					//echo $num_toile_prec;
					?>
					<a href="<?php echo get_permalink($prev_post_id); ?>" class="prev-post-button"><i class="fa-solid fa-arrow-left"></i></a>
					<?php

					endwhile;
					
				endif;
				
				// Réinitialiser les données de la requête
				wp_reset_postdata();

				// Construction des paramètres personnalisés pour la requête suivante
				$next_post_args = array(
					'post_type'     => 'toile', // type du post
					'meta_key' => 'num_toile', // Key pour le champ ACF qui va servir de tri
					'meta_value' => $num_toile, // Valeur
					'meta_compare' => '>', // Opérateur
					
					'posts_per_page' => 1
				);
				
				// Requête personnalisée
				$next_post_query = new WP_Query($next_post_args);
				
				// Boucle
				if ($next_post_query->have_posts()):

					while( $next_post_query->have_posts() ) : $next_post_query->the_post();

					$next_post_id = get_the_ID();
					$num_toile_prec = get_field('num_toile');
					//echo $num_toile_prec;
					?>
					<a href="<?php echo get_permalink($next_post_id); ?>" class="prev-post-button"><i class="fa-solid fa-arrow-right"></i></a>
					<?php

					endwhile;
					
				endif;
				
				// Réinitialiser les données de la requête
				wp_reset_postdata();



				?>














				</div>


				<?php the_post_thumbnail('medium');	?>
					
				<table>
					<tr>
						<td class="libelle">Titre</td>
						<td><?php if(get_field( 'titre' )){the_field('titre');} ?></td>
					</tr>
					<tr>
						<td class="libelle">Numéro de toile</td>
						<td><?php if(get_field( 'num_toile' )){the_field('num_toile');} ?></td>
					</tr>
					<tr>
						<td class="libelle">Année</td>
						<td><?php if(get_field( 'annee' )){the_field('annee');} ?></td>
					</tr>
					<tr>
						<td class="libelle">Dimensions</td>
						<td><?php if(get_field( 'dimensions' )){echo str_replace('X',' x ',get_field('dimensions'));} ?></td>
					</tr>
					<tr>
						<td class="libelle">Disponibilité</td>
						<td>
							<?php
								if(get_field( 'collection' ))
								{
									$field = get_field_object( 'collection' );
									$value = $field['value'];
									$label = $field['choices'][ $value ];
									
									if($value=='paul'){
										echo '<div class="dispo"></div>';
									}
								}
							?>
						</td>
					</tr>
					<!--<tr>
						<td class="libelle">Collection</td>
						<td><?php 
						if(get_field( 'collection' ))
						{
							$field = get_field_object( 'collection' );
							$value = $field['value'];
							$label = $field['choices'][ $value ];

							echo $label;
						}
							
							
						?></td>
					</tr>-->
				</table>

				<p><a href="<?php echo the_permalink( 9 ) ?>?objet=<?php the_title(); ?>">Poser une question</a></p>
			</div>

		</div>

		<figure class="featured-media">

			<div class="featured-media-inner section-inner">

				<?php
				the_post_thumbnail('full');
				?>

			</div><!-- .featured-media-inner -->

		</figure><!-- .featured-media -->
	</div>
	<?php
}
