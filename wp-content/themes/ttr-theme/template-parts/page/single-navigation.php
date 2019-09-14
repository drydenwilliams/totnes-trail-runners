<div class="job-navigation">
            <a href="<?php echo site_url(); ?>">&#8592; Back to all jobs</a>
          
            <div class="job-categories">
              <?php
              global $post;
              $categories = get_the_category();

                  foreach ($categories as $category) :

                    $exclude = get_the_ID();
                    $posts = get_posts('posts_per_page=4&category='. $category->term_id);

                      foreach($posts as $post) :
                      if( $exclude != get_the_ID() ) { ?>

                              <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="back-home"> Link to actual post</a>

                  <?php } endforeach; ?>

              <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" title="View all" class="category"><i class="i-right-double-arrow"></i> View all <?php echo $category->name; ?> jobs &#8594;</a>
              <?php  endforeach; wp_reset_postdata(); ?>
            </div>

          </div>  