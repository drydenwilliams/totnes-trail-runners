<?php
$is_contract = false;
$is_recruiter = false;

foreach (get_the_terms(get_the_ID(), 'job_type') as $cat) {
  if ($cat->name === 'Contract') {
    $is_contract = true;
    $items[] = 'job-card--contract';
  } else {
    $is_contract = false;
  }
}


$contracts = get_field('advert_type');
foreach( $contracts as $contract ) {
  if ($contract === 'Recruiter') {
    $is_recruiter = true;
    $items[] = 'job-card--recruiter';
  } else {
    $is_recruiter = false;
  }
}
?>
<?php get_header(); ?>

  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

    <section class="jumbotron text-center">
      <div class="container">

        <?php foreach((get_the_category()) as $category) {
                if ($category->cat_name === 'Recruiter') { echo ' <span class="badge badge-pill badge-info">Recruitment Company</span>'; }
              } ?>
          <h1 class="jumbotron-heading"><?php the_title(); ?></h1>
          <p class="lead text-muted">



            <?php
              $lower_range = get_field( "lower_range" );
              $upper_range = get_field( "upper_range" );
              foreach (get_the_terms(get_the_ID(), 'job_type') as $cat) {
                // echo 'job_type: ' . $cat->term_id;
                echo '<strong>'. $cat->name. '</strong>';
              }
            ?>

            <?php
              if ($lower_range !== '0') {
                echo 'for';
                echo get_template_part('template-parts/post/salary');
              }
            ?>

            <?php if (!is_user_logged_in() && $is_contract && !$is_recruiter) : ?>
              <span></span>
            <?php else : ?>
              at <strong><?php echo get_field( "company_name" ); ?></strong>
            <?php endif; ?>

             in <strong><?php echo get_field( "company_address" ); ?></strong>

            <?php

              function slugify ($text) {
                $text = preg_replace('~[^\pL\d]+~u', '-', $text);
                $text = preg_replace('~-+~', '-', $text);
                $text = strtolower($text);
                return $text;
              }

              $company_name = get_field( "company_name" );

              $event_label = slugify(get_the_title()) . '-' . slugify($company_name);
            ?>

          </p>
          <p>
            <?php if (!is_user_logged_in() && $is_contract && !$is_recruiter) : ?>
              <a class="btn btn-primary hidden" data-toggle="modal" data-target="#loginModal">Apply for the Job</a>
            <?php else : ?>
              <?php $email_link = get_field( "company_contact_email"); ?>
              <?php if (!empty($email_link)) : ?>
              <a data-category="Email Link" data-tab="mail" data-label="Jumbotron - <?php echo $event_label; ?>" data-action="Click" data-redirect="mailto:<?php echo get_field( "company_contact_email" ); ?>" class="btn btn-primary ga-event">Apply by email</a>
              <?php endif; ?>
              <a data-category="Outbound Link" data-label="Jumbotron - <?php echo $event_label; ?>" data-action="Click" data-redirect="<?php echo get_field( "company_website" ); ?>?utm_source=developerjobsboard&utm_medium=website" data-tab="open" class="btn btn-secondary ga-event">Visit Website</a>

            <?php endif; ?>
          </p>
        </div>
      </section>

      <main id="main" class="main-content" role="main">
        <section>
          <div class="container">


          <?php echo get_template_part('template-parts/page/single-navigation'); ?>

            <div class="row">
              <div class="col-md-8 col-md-pull-4 job-page-content">

                <h2>About the job</h2>

                <?php if (!is_user_logged_in() && $is_contract && !$is_recruiter) : ?>
                  <span class="line"></span>
                  <span class="line"></span>
                  <span class="line"></span>
                  <span class="line"></span>
                  <span class="line"></span>
                  <span class="line"></span>
                <?php elseif ($is_recruiter && !is_user_logged_in()) : ?>
                  <?php the_content(); ?>
                <?php elseif (!$is_recruiter || !$is_contract) : ?>
                  <?php the_content(); ?>
                <?php endif; ?>

                <h2>How to apply</h2>
                <?php if (!is_user_logged_in() && $is_contract && !$is_recruiter) : ?>
                  <span class="line"></span>
                  <span class="line"></span>
                  <span class="line"></span>
                  <span class="line"></span>
                  <span class="line"></span>
                  <span class="line"></span>
                <?php elseif ($is_recruiter && !is_user_logged_in()) : ?>
                  <?php the_excerpt(); ?>
                <?php elseif (!$is_recruiter || !$is_contract) : ?>
                  <?php the_excerpt(); ?>
                <?php endif; ?>



                <?php if (!is_user_logged_in() && $is_contract && !$is_recruiter) : ?>
                  <a class="btn btn-primary ga-event" data-toggle="modal" data-target="#loginModal">Apply for the Job</a>
                <?php else : ?>
                <?php $email_link = get_field( "company_contact_email"); ?>
                  <?php if (!empty($email_link)) : ?>
                    <a data-category="Email Link" data-tab="mail" data-label="<?php echo $event_label; ?>" data-action="Click" data-redirect="mailto:<?php echo get_field( "company_contact_email" ); ?>" class="btn btn-primary ga-event">Apply by email</a>
                  <?php endif; ?>
                  <a data-category="Outbound Link" data-label="<?php echo $event_label; ?>" data-action="Click" data-redirect="<?php echo get_field( "company_website" ); ?>?utm_source=developerjobsboard&utm_medium=website" data-tab="open" class="btn btn-secondary ga-event">Visit Website</a>
                <?php endif; ?>


              </div>
              <div class="col-md-4 col-md-push-8 job-page-sidebar">
                <h3>Skills</h3>
                <?php
                  foreach (get_the_terms(get_the_ID(), 'skills') as $cat) {
                    // echo 'skills: ' . $cat->term_id;
                    echo '<a class="badge badge-pill badge-info" href="'. get_tag_link($cat->term_id) . '">'. $cat->name. '</a>';
                  }
                ?>


                  <h3>Job type</h3>

                  <?php foreach (get_the_terms(get_the_ID(), 'job_type') as $cat): ?>
                  <?php echo '<a class="badge badge-pill badge--'. strtolower($cat->name) .'" href="'. get_tag_link($cat->term_id) . '">'. $cat->name. '</a>'; ?>
                  <?php endforeach; ?>

                <h3>Job level</h3>
                <?php foreach (get_the_terms(get_the_ID(), 'level') as $cat): ?>
                <?php echo '<a class="badge badge-pill badge-secondary" href="'. get_tag_link($cat->term_id) . '">'. $cat->name. '</a>'; ?>
                <?php endforeach; ?>

                  <h3>Posted on</h3>
                  <?php the_date() ?>
              </div>

            </div>

          <?php endwhile; ?>
          <?php else: ?>
            <h1>No posts here!</h1>
          <?php endif; ?>
          </div>
        </section>
      </main>


<?php get_footer(); ?>
