<?php get_header(); ?>

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


<?php $current_category = single_cat_title("", false); ?>

  <div class="container">
    <div class="job-navigation">
      <a href="<?php echo site_url(); ?>">&#8592; Back to all jobs</a>
    </div>
    <div class="row">


      <?php

// var_dump(get_the_terms(get_the_ID(), 'skills'));

        // The Arguments
        $args = array(
            'post_type' => 'Jobs',
            'category_name' => $current_category,
            'tax_query' => array(
                array(
                    'taxonomy' => 'skills',
                    'terms' => 'mongo',
                    'field' => 'slug',
                    'include_children' => true,
                    'operator' => 'IN'
                )),
            'meta_query' => array(
              array(
                'key' => 'contract',
                'value' => 'full-time',
              ))
        );  
        

        // The Query
        $the_query = new WP_Query( $args ); ?>

        <?php

        // If we have the posts...
        if ( $the_query->have_posts() ) : ?>

        <!-- Start the loop the loop --> 
            <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

                <?php get_template_part( 'template-parts/post/content' );  ?>

            <?php endwhile; endif; // end of the loop. ?>

        <?php wp_reset_postdata(); ?>
    </div>

  </div>


<?php get_footer(); ?>
