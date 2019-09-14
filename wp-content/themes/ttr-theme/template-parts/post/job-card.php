<?php
$is_recruiter = false;
$is_contract = false;
$items = array('col-md-12', 'job-card');
$posttags = get_the_tags();

if ($posttags) {
  foreach($posttags as $tag) {
    $items[] = lcfirst($tag->name);
  }
}

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

$classNames = implode(" ", $items);

?>



<div class="<?php echo $classNames ?>">
  <div class="card flex-md-row align-items-center box-shadow">

    <div class="card-body card-title-box d-flex flex-column">

      <?php if (!is_user_logged_in() && $is_contract && !$is_recruiter) : ?>
        <a class="hidden" data-toggle="modal" data-target="#loginModal">
      <?php else : ?>
        <a href="<?php the_permalink(); ?>">
      <?php endif; ?>


        <?php $contracts = get_field('advert_type'); ?>
        <?php foreach( $contracts as $contract ): ?>
          <?php if ($contract === 'Recruiter') { echo '<span class="badge badge-pill badge-info">Recruitment Company</span>'; } ?>
        <?php endforeach; ?>

        <h2 class="card-title">
          <?php the_title(); ?>
        </h2>

          <?php if (!is_user_logged_in() && $is_contract && !$is_recruiter) : ?>
            <h3 class="card-subtitle text-muted" >
              <span class="line"></span>
            </h3>
          <?php else : ?>
            <h3 class="card-subtitle text-muted">
                <?php echo get_field( "company_name" ); ?>
              </h3>
          <?php endif; ?>


      </a>

    </div>

    <div class="card-body card-excerpt d-flex flex-column">

      <?php if (!is_user_logged_in() && $is_contract && !$is_recruiter) : ?>
      <p class="content content--hidden">
          <span class="line"></span>
          <span class="line"></span>
          <span class="line"></span>
        </p>
      <?php else : ?>
        <p class="content">
          <?php echo wp_trim_words(get_the_content(), 15); ?>
        </p>
      <?php endif; ?>


      <div class="levels">
        <?php
          // For a new post under 1 week
          $diff = human_time_diff(get_the_time('U'), current_time('timestamp'));

          if (
            strpos($diff, 'min') !== false ||
            strpos($diff, 'mins') !== false ||
            strpos($diff, 'hour') !== false ||
            strpos($diff, 'hours') !== false ||
            strpos($diff, 'day') !== false ||
            strpos($diff, 'days') !== false
          ) {
            echo '<span class="badge badge-pill badge--new">New</span>';
          }
        ?>
        <?php foreach (get_the_terms(get_the_ID(), 'job_type') as $cat): ?>
        <?php echo '<a class="badge badge-pill badge--'. strtolower($cat->name) .'" href="'. get_tag_link($cat->term_id) . '">'. $cat->name. '</a>'; ?>
        <?php endforeach; ?>
      </div>

    </div>


    <div class="card-body card-meta d-flex flex-column">
      <div>
        <?php get_template_part('template-parts/post/salary'); ?>
      </div>
      <div>

      <?php
        foreach (get_the_terms(get_the_ID(), 'job_type') as $cat) {
          if ($cat->name === 'Contract' || $cat->name === 'Intern') {
            echo get_field( "contract_length" );
          }
        }
      ?>
      </div>
      <div> <?php echo get_field( "company_address" ); ?></div>
      <div><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago'; ?></div>
    </div>


  </div>
</div>