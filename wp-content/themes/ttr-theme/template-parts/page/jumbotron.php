
<section class="home-section">
  <header class="intro container">
    <div class="row">
    <div class="col-md-12 col-lg-5 col-xl-6 col-lg-offset-1 col-xl-offset-0">
        <h1 class="jumbotron-heading">
            <?php
            if (is_home() || is_front_page()) {
              bloginfo('description');
            } else {

              $skills = get_query_var('skills');
              $job_type = get_query_var('job_type');
              $level = get_query_var('level');
              $location = get_query_var('location');
              $current_category = strtolower(single_cat_title("", false));

              if ($current_category === $location) {
                echo 'The Best Jobs In ' . ucfirst($location);
              } else {
                echo single_cat_title('The Best ');

                // if ($job_type) echo ', ' . ucfirst($job_type);
                // if ($level) echo ', ' . ucfirst($level);

                echo ' Jobs';

                if ($location && $current_category !== $location) {
                  echo ' in ' . ucfirst($location);
                }

              }

            }
          ?>
        </h1>
        <a href="<?php echo site_url(); ?>/post-a-job" class="btn btn--cta my-2 ga-event" data-category="CTA" data-label="Jumbotron - Post a Job" data-action="Click">Post a job for £99</a>
      </div>
    </div>
  </header>
</section>