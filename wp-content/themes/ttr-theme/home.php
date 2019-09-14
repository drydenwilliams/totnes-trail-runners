<?php get_header(); ?>

<section class="jumbotron text-center">
  <div class="container">
    <h1 class="jumbotron-heading">Blog</h1>
  </div>
</section>

<main class="main-content">
  <div class="container">
    <div class="post-list">

      <!-- Start the Loop. -->
      <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

      <article class="card post-item">
        <div class="card__inner">
          <?php if (has_post_thumbnail( $post->ID ) ): ?>
            <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
            <!-- <div id="custom-bg" style="background-image: url('<?php echo $image[0]; ?>')"></div> -->
            <img class="card-img-top" src="<?php echo $image[0]; ?>" alt="Card image cap">
          <?php endif; ?>

          <div class="card-body">
            <h2 class="card-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
            <div class="entry">
              <?php
                echo wp_trim_words(get_the_content(), 15);
              ?>
            </div>
          </div>

          <div class="card-footer text-muted">
            <small><?php the_time('F jS, Y'); ?></small>
          </div>
        </div>
      </article>


      <!-- Stop The Loop (but note the "else:" - see next line). -->

      <?php endwhile; ?>

      <!-- REALLY stop The Loop. -->
      <?php endif; ?>


    </div>
  </div>
</main>

<?php get_footer(); ?>