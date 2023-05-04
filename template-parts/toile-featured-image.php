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
			<table>
				<tr>
					<td class="libelle">Titre</td>
					<td><?php the_field('titre'); ?></td>
				</tr>
				<tr>
					<td class="libelle">Numéro de toile</td>
					<td><?php the_field('num_toile'); ?></td>
				</tr>
				<tr>
					<td class="libelle">Année</td>
					<td><?php the_field('annee'); ?></td>
				</tr>
				<tr>
					<td class="libelle">Dimensions</td>
					<td><?php echo str_replace('X',' x ',get_field('dimensions')); ?></td>
				</tr>
				<tr>
					<td class="libelle">Collection</td>
					<td><?php 
						$field = get_field_object( 'collection' );
						$value = $field['value'];
						$label = $field['choices'][ $value ];

						echo $label;
						
					?></td>
				</tr>
				<tr>
					<td class="libelle">&nbsp;</td>
					<td><a href="<?php echo the_permalink( 9 ) ?>?objet=<?php the_title(); ?>">Poser une question</a></td>
				</tr>
				
			</table>

		</div>

		<figure class="featured-media">

			<div class="featured-media-inner section-inner">

				<?php
				the_post_thumbnail();

				$caption = get_the_post_thumbnail_caption();

				if ( $caption ) {
					?>

					<figcaption class="wp-caption-text"><?php echo wp_kses_post( $caption ); ?></figcaption>

					<?php
				}
				?>

			</div><!-- .featured-media-inner -->

		</figure><!-- .featured-media -->
	</div<
	<?php
}
