<?php get_header(); ?>


<main class="content-block ">
<div class="container-fluid">
	<div class="row medium-gutters">
		<div class="col-md-2">
		<ul>
    <?php
    global $id;
    wp_list_pages( array(
        'title_li'    => '',
        'child_of'    => $id,
        'show_date'   => 'modified',
        'date_format' => $date_format
    ) );
    ?>
</ul>
		</div>	
		<div class="col-md-10">
		<?php while ( have_posts() ) : the_post(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->
	<div class="entry-content">

		<?php
			the_content();
		?>

	</div><!-- .entry-content -->
</article><!-- #post-## -->

<?php endwhile; ?>
	</div>
	</div>
	</div>
</main>



<?php get_footer(); ?>
