<?php get_header(); ?>






<main>
  <section class="content-block p-5 feature-block">
    <div class="">
      <div class="row medium-gutters">
        <div class="col-lg-6 featured-article">
        <?php
  $args = array(
        'posts_per_page' => 1,
        'meta_key' => 'meta-checkbox',
        'meta_value' => 'yes'
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
        <div class="col-lg-4 posts">
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
        <div class="col-lg-2 news">
        <h4 class="text-center text-uppercase">Trail news</h4>
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
  </section>

  <section class="content-block p-5">
    Most recent
    <div class="row medium-gutters">

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
  </section>

  <section class="content-block p-5">
    Partnership
    <div class="row medium-gutters align-items-center">

    <?php
  $args = array(
        'posts_per_page' => 1,
        'post_type' => 'partnerships',
        'meta_key' => 'meta-checkbox',
        'meta_value' => 'yes'
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

  <!-- <section class="content-block p-5">
    Events
  </section>

  <section class="content-block p-5">
    Shop
  </section> -->

  <section class="content-block p-5">
    Insta-piration

    What and who we find inspiring on instagram

    https://www.instagram.com/cotezi/
  </section>
</main>




<?php get_footer(); ?>
