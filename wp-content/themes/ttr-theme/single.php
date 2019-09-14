<?php get_header(); ?>

<main class="content-block p-5">
	<div class="row medium-gutters">
    <div class="col-md-8 order-md-2">
    <article class="blog-post">

    <header class="">
  <div class="container">
    <h1 class="h1 dm-serif jumbotron-heading">
      <?php the_title(); ?>
    </h1>
    <p>
    <?php the_excerpt(); ?>

    <div class="d-block d-md-none">
    <p>
    <?php  
$categories = get_the_category();
$separator = ' ';
$output = '';
if ( ! empty( $categories ) ) {
    foreach( $categories as $category ) {
        $output .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" alt="' . esc_attr( sprintf( __( 'View all posts in %s', 'textdomain' ), $category->name ) ) . '">' . esc_html( $category->name ) . '</a>' . $separator;
    }
    echo trim( $output, $separator );
}


?>
</p>
<p>
<?php $author = get_the_author(); echo $author;?>
</p>
<p class="jumbotron-meta"><?php the_time('F jS, Y'); ?></p>

</div>
</p>
    
  </div>
</header>

<div class="">
  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

    <div class="post">
      <?php the_content(); ?>
    </div>

    

  <?php endwhile; ?>
  <?php endif; ?>
</div>


</article>
    </div>
		<aside class="col-md-2 order-md-1">
    
   <div class="d-none d-md-block">
   <p>
    <?php  
$categories = get_the_category();
$separator = ' ';
$output = '';
if ( ! empty( $categories ) ) {
    foreach( $categories as $category ) {
        $output .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" alt="' . esc_attr( sprintf( __( 'View all posts in %s', 'textdomain' ), $category->name ) ) . '">' . esc_html( $category->name ) . '</a>' . $separator;
    }
    echo trim( $output, $separator );
}


?>
</p>
<p>
<?php $author = get_the_author(); echo $author;?>
</p>
<p class="jumbotron-meta"><?php the_time('F jS, Y'); ?></p>

</div>
<div class="social">
  <div>share twitter</div>
</div>
    </aside>
</div>
</main>


<?php get_footer(); ?>