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
						// Récupérer la valeur du champ ACF 'num_toile' pour le post actuel
						$current_num_toile = get_field('num_toile');

						// Requête pour l'entrée précédente (num_toile plus faible)
						$args_prev = array(
							'post_type' => 'toile',
							'posts_per_page' => 1,
							'meta_key' => 'num_toile',
							'orderby' => 'meta_value_num',
							'order' => 'DESC',
							'meta_query' => array(
								array(
									'key' => 'num_toile',
									'value' => $current_num_toile,
									'compare' => '<',
									'type' => 'NUMERIC'
								)
							)
						);
						
						$prev_post = new WP_Query($args_prev);
						
						if ($prev_post->have_posts()) :
							while ($prev_post->have_posts()) : $prev_post->the_post(); ?>
								<a href="<?php the_permalink(); ?>" class="prev-post-button"><i class="fa-solid fa-arrow-left"></i> <?php if(get_field( 'titre' )){the_field('titre');} ?></a>
							<?php endwhile;
							wp_reset_postdata();
						endif;
						
						// Requête pour l'entrée suivante (num_toile plus élevé)
						$args_next = array(
							'post_type' => 'toile',
							'posts_per_page' => 1,
							'meta_key' => 'num_toile',
							'orderby' => 'meta_value_num',
							'order' => 'ASC',
							'meta_query' => array(
								array(
									'key' => 'num_toile',
									'value' => $current_num_toile,
									'compare' => '>',
									'type' => 'NUMERIC'
								)
							)
						);
						
						$next_post = new WP_Query($args_next);
						
						if ($next_post->have_posts()) :
							while ($next_post->have_posts()) : $next_post->the_post(); ?>
								<a href="<?php the_permalink(); ?>" class="prev-post-button"><?php if(get_field( 'titre' )){the_field('titre');} ?> <i class="fa-solid fa-arrow-right"></i></a>
							<?php endwhile;
							wp_reset_postdata();
						endif;
						
						
						?>
				</div>


				<?php the_post_thumbnail('medium');	?>
					
				<table>
					<tr>
						<td class="libelle">Titre</td>
						<td><?php if(get_field( 'titre' )){the_field('titre');} ?></td>
					</tr>
					<tr>
						<td class="libelle">Année</td>
						<td><?php if(get_field( 'annee' )){the_field('annee');} ?></td>
					</tr>
					<tr>
						<td class="libelle">Numéro de toile</td>
						<td><?php if(get_field( 'num_toile' )){the_field('num_toile');} ?></td>
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
