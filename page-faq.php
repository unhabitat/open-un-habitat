<?php
/*
Template Name: FAQ template
*/
?>
<?php get_header(); ?>
<div class="spacer"></div>
<div class="container">

<?php

function show_faq_items($category_name){

echo '<div class="col-md-4">';

$args = array( 'post_type' => 'faq', 'posts_per_page' => 100, 'category_name' => $category_name,'orderby' => 'menu_order title', 'order' => 'DESC' );
$loop = new WP_Query( $args );
while ( $loop->have_posts() ) : $loop->the_post();	
?>

<div class="row">
	<div class="col-md-12">
		<div class="gray-col">
			<h2>
				<?php the_title(); ?>
			</h2>

			<?php the_content(); ?>
		</div>
	</div>
</div>

<?php 
endwhile;
wp_reset_postdata();

echo '</div>';

}

?>

	<div class="row">
		<?php 
		show_faq_items("left-column");
		show_faq_items("middle-column");
		show_faq_items("right-column");
		?>
	</div>
</div>
<?php include( TEMPLATEPATH .'/footer-scripts.php' ); ?>
<?php get_footer(); ?>