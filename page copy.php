<?php
/*
 * Template Name: Custom Full Width
 * description: >-
  Page template without sidebar
 */
?>
<?php BsWp::get_template_parts( array( 
	'parts/shared/html-header', 
	'parts/shared/header' 
) ); ?>

<div class="content">
	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

		<h2>
			<?php the_title(); ?> zsdfasdgafbaertgasdfvgargasrdgasdfasdf
		</h2>
		<?php the_content(); ?>
		<?php comments_template( '', true ); ?>

	<?php endwhile; ?>
</div>

<?php BsWp::get_template_parts( array( 
	'parts/shared/footer',
	'parts/shared/html-footer' 
) ); ?>
