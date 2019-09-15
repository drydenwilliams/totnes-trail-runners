<?php get_header(); ?>






<main class="container-fluid">
  <section class="content-block feature-block">
    <div class="">
      <div class="row medium-gutters">
        <div class="col-md-8 col-xl-6 featured-article">
        <?php
  $args = array(
        'posts_per_page' => 1,
        'meta_key'		=> 'featured',
	'meta_value'	=> true
    );
    $featured_query = new WP_Query($args);
 
    if ($featured_query->have_posts()): while($featured_query->have_posts()): $featured_query->the_post(); ?>
      <article class="text-center">
      <?php if (has_post_thumbnail()) : ?>
      <figure> 
        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
      </figure>
      <?php
          $categories = get_the_category();
          if ( ! empty( $categories ) ) {
              echo '<a class="tag-link" href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '">' . esc_html( $categories[0]->name ) . '</a>';
          }
        ?>
      <h2 class="h1 dm-serif">
      <a href="<?php the_permalink(); ?>">
        <?php the_title(); ?>
        </a>
      </h2>
      <p ><?php the_excerpt();?></p>
      <hr/>
      <p class="text-uppercase">
        By
        <a href="<?php the_author_posts() ?>" rel="author"><?php the_author(); ?> </a>
      </p>
      

        </article>
      <?php
      endif;
      endwhile; else:
      endif;
      wp_reset_postdata(); 
      ?>

        </div>
        <div class="col-md-4 col-xl-4 posts">
        <?php
          $args = array(
            'post_type' => 'post',
            'tag__not_in' => array('9'),
            'posts_per_page' => 2,
          );
          $category_posts = new WP_Query($args);

          if($category_posts->have_posts()) : 
              while($category_posts->have_posts()) : 
                $category_posts->the_post();
        ?>

        <article>

        <?php if (has_post_thumbnail()) : ?>
              <?php endif; ?>

        <figure> 
          <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
        </figure>
        <?php
          $categories = get_the_category();
          if ( ! empty( $categories ) ) {
              echo '<a class="tag-link" href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '">' . esc_html( $categories[0]->name ) . '</a>';
          }
        ?>
        <h2 class="h3">
        <a href="<?php the_permalink(); ?>">
          <?php the_title(); ?>
        </a>
        </h2>
        <p ><?php the_excerpt();?></p>


          </article>   

        <?php
              endwhile;
          else: 
        ?>

              Oops, there are no posts.

        <?php
          endif;
          wp_reset_postdata();
        ?>

        </div>
        <div class="col-xl-2 news">
        <h4 class="text-center text-uppercase news-title ">Trail news</h4>
          <div class="news-wrapper">
          <?php
            $args = array(
              'post_type' => 'news',
              'posts_per_page' => 4,
            );
            $news_query = new WP_Query($args);

            if($news_query->have_posts()) : 
                while($news_query->have_posts()) : 
                  $news_query->the_post();
          ?>

          <article class="post-summary text-center">

          <?php if (has_post_thumbnail()) : ?>
                <?php endif; ?>

            <h3>
            <a href="<?php the_permalink(); ?>">
            <a href="<?php the_permalink(); ?>">
            <?php the_title(); ?>
                </a>
            </a>
            </h3>
            </article>   

            <hr />

          <?php
                endwhile;
            else: 
          ?>

                Oops, there are no posts.

          <?php
            endif;
          ?>

        </div>
        </div>
      </div>
    </div>
  </section>

  <section class="content-block standard-block no-pad-top ">
            <div class="line-header">
            <h2>Most recent</h2>
            </div>
  </section>

  <section class="content-block">
    
  <div class="hero-slider__wrapper">
  <!-- <button type="button" class="slick-prev">Previous</button>
  <button type="button" class="slick-next">Next</button> -->

    <div class="hero-slider">

    
    <?php
          $args = array(
            'post_type' => 'post',
            'posts_per_page' => 4,
            'orderby' => 'post_date',
	          'order' => 'DESC',
          );
          $category_posts = new WP_Query($args);

          if($category_posts->have_posts()) : 
              while($category_posts->have_posts()) : 
                $category_posts->the_post();
        ?>

        <article class="col-md-3">

        <?php if (has_post_thumbnail()) : ?>
              <?php endif; ?>

        <figure> 
          <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
        </figure>
        <?php
          $categories = get_the_category();
          if ( ! empty( $categories ) ) {
              echo '<a class="tag-link" href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '">' . esc_html( $categories[0]->name ) . '</a>';
          }
        ?>
        <h2 class="h3">
        <a href="<?php the_permalink(); ?>">
          <?php the_title(); ?>
        </a>
        </h2>
        <p ><?php the_excerpt();?></p>


          </article>   

        <?php
              endwhile;
          else: 
        ?>

              Oops, there are no posts.

        <?php
          endif;
          wp_reset_postdata();
        ?>
          </div>
        </div>
  </section>

  <section class="content-block standard-block no-pad-top">
            <div class="line-header">
            <h2>Partnership</h2>
            </div>
  </section>

  <section class="content-block">
    <div class="row medium-gutters align-items-center">

    <?php
  $args = array(
        'posts_per_page' => 1,
        'post_type' => 'partnerships',
        'meta_key'		=> 'featured',
	'meta_value'	=> true
    );
    $featured_query = new WP_Query($args);
 
    if ($featured_query->have_posts()): while($featured_query->have_posts()): $featured_query->the_post(); ?>
    
      
      <div class="col-md-6">
      <figure> 
        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
      </figure>
  </div>
  <div class="col-md-6 text-center">
        <?php
          $categories = get_the_category();
          if ( ! empty( $categories ) ) {
              echo '<a class="tag-link" href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '">' . esc_html( $categories[0]->name ) . '</a>';
          }
        ?>
      <h2 class="h1 dm-serif">
      <a href="<?php the_permalink(); ?>">
        <?php the_title(); ?>
        </a>
      </h2>
      <p ><?php the_excerpt();?></p>
      <hr/>
  </div>

      

      <?php
      endwhile; else:
      endif;
      wp_reset_postdata(); 
      ?>

    </div>
  </section>

  <!-- <section class="content-block">
    Events
  </section>

  <section class="content-block">
    Shop
  </section> -->

  <section class="content-block standard-block no-pad-top">
            <div class="line-header">
            <h2>Insta-spiration</h2>
            </div>
  </section>


  <section class="content-block">
  <div class="hero-slider__wrapper">
    <div class="hero-slider">
      
    <?php
          $args = array(
            'post_type' => 'instaspiration',
            'posts_per_page' => 4,
            'orderby' => 'post_date',
	          'order' => 'DESC',
          );
          $category_posts = new WP_Query($args);

          if($category_posts->have_posts()) : 
              while($category_posts->have_posts()) : 
                $category_posts->the_post();
        ?>

        <article>

        <?php if (has_post_thumbnail()) : ?>
              <?php endif; ?>

        <figure class="insta-spiration"> 
          <a href="<?php echo '//instagram.com/p/' . get_field('photoid'); ?>" target="_blank">
            <?php the_post_thumbnail(); ?>
            <div class="hover-over">
              <div style="width: 40px; margin-bottom: 10px;">
              <img src="<?php echo get_template_directory_uri(); ?>/assets/images/ig-logo.svg" />
              </div>
<h5><?php echo '@' . get_field('username'); ?></h5>
              </div>
          </a>
         
        </figure>

          </article>   

        <?php
              endwhile;
          else: 
        ?>

              Oops, there are no posts.

        <?php
          endif;
          wp_reset_postdata();
        ?>
        </div>
  </section>
</main>




<?php get_footer(); ?>
