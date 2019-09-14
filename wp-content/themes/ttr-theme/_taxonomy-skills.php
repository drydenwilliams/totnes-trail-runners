<?php 
$current_category = single_cat_title("", false);
get_header();
?>

<section class="jumbotron text-center">
  <div class="container">
    <h1 class="jumbotron-heading"><?php bloginfo('name'); ?></h1>
    <p class="lead text-muted">
    <?php single_cat_title() ?>
    </p>
    <p>
      <a href="<?php echo site_url(); ?>/post-a-job" class="btn btn-primary my-2">Post a job for £99</a>
    </p>
  </div>
</section>

  <div class="container">
    <div class="job-navigation">
      <a href="<?php echo site_url(); ?>">&#8592; Back to all jobs</a>
    </div>
    <div class="row">
      <?php $args = array('post_type' => 'Jobs', 'tax_query' => array(
          array(
              'taxonomy' => 'skills',
              'terms' => $current_category,
              'field' => 'slug',
              'include_children' => true,
              'operator' => 'IN'
          )
      )) ?>
      <?php $loop = new WP_Query($args); ?>
      <?php if ( $loop->have_posts() ) : while ( $loop->have_posts() ) : $loop->the_post(); ?>
        <?php get_template_part('template-parts/post/content'); ?>
      <?php endwhile; ?>
      <?php else: ?>
          <h1>No posts here!</h1>
      <?php endif; ?>
      <?php wp_reset_postdata(); ?>
    </div>
  </div>


<?php get_footer(); ?>
