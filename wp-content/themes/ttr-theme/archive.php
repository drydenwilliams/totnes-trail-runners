<?php
/*
Template Name: Archives
*/
get_header(); ?>

<main class="content-block p-5">
	<div class="row medium-gutters">
		<div class="col-md-2">
		<?php
$categories = get_categories( array(
    'orderby' => 'name',
	'order'   => 'ASC',
	'hide_empty'   => true
) );
 
foreach( $categories as $category ) {
    $category_link = sprintf( 
        '<a href="%1$s" alt="%2$s">%3$s</a>',
        esc_url( get_category_link( $category->term_id ) ),
        esc_attr( sprintf( __( 'View all posts in %s', 'textdomain' ), $category->name ) ),
        esc_html( $category->name )
    );
     
    echo '<p>' . $category_link . ' (' . $category->count . ')' . '</p> ';
} 

?>
		</div>
		<div class="col-md-10">
			<h1><?php echo single_cat_title(); ?></h1>
			<div class="row">
			<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
				<div class="col-md-4">
				<article>
				<?php if (has_post_thumbnail()) : ?>
				<figure> 
					<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
				</figure>
				<a href="" class="tag-link">
					<?php the_category(', '); ?>
				</a>
				<h2 class="h3">
					<?php the_title(); ?>
				</h2>
				<p ><?php the_excerpt();?></p>
				

					</article>
</div>
									<?php endif; endwhile; endif;
			?>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>