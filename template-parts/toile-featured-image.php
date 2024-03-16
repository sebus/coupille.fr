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
				<!--<p><i class="at-arrow-left"></i> <a href="#" onclick="history.go(-1)">Retourner au catalogue</a></p>-->
				<?php the_post_thumbnail('medium');	?></td>
					
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

				

				<p><i class="at-envelope-question"></i> <a href="<?php echo the_permalink( 9 ) ?>?objet=<?php the_title(); ?>">Poser une question</a></p>
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
