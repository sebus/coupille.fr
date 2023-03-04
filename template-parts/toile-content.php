<?php
/**
 * The default template for displaying content
 *
 * Used for both singular and index.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<?php

	get_template_part( 'template-parts/toile-entry-header' );  

	get_template_part( 'template-parts/toile-featured-image' );

	?>

	<div class="post-inner">

		<div class="entry-content">


			
			<table>
				<tr>
					<td>Titre</td>
					<td><?php the_field('titre'); ?></td>
				</tr>
				<tr>
					<td>Numéro de toile</td>
					<td><?php the_field('num_toile'); ?></td>
				</tr>
				<tr>
					<td>Année</td>
					<td><?php the_field('annee'); ?></td>
				</tr>
				<tr>
					<td>Dimensions</td>
					<td><?php echo str_replace('X',' x ',get_field('dimensions')); ?></td>
				</tr>
				<tr>
					<td>Collection</td>
					<td><?php 
						$field = get_field_object( 'collection' );
						$value = $field['value'];
						$label = $field['choices'][ $value ];

						echo $label;
						
					?></td>
				</tr>
				
			</table>

		</div><!-- .entry-content -->

	</div><!-- .post-inner -->

</article><!-- .post -->
