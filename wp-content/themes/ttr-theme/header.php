<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="index,follow" />

  <link rel="icon" type="image/png" sizes="32x32" type="image/ico" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon-32x32.png" />
  <link rel="apple-touch-icon" sizes="60x60" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon-60x60.png" />
  <link rel="apple-touch-icon" sizes="120x120" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon-120x120.png" />

  <!-- WP HEAD -->
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?> >

  <header class="site-header">
  <nav class="navbar navbar-expand-lg navbar-light">
      <div class="container">
        <a class="navbar-brand" href="<?php echo site_url(); ?>"><?php bloginfo('name'); ?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample07" aria-controls="navbarsExample07" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExample07">
          <ul class="navbar-nav ml-auto">

            <?php if ( is_user_logged_in() ) : ?>
              <li class="nav-item nav-item-logout"><a class="nav-link" href="<?php echo wp_logout_url(); ?>">Logout</a><li>
            <?php else : ?>
              <li class="nav-item nav-item-login"><a class="nav-link" data-toggle="modal" data-target="#loginModal">Login</a><li>
            <?php endif; ?>

            <?php
            wp_nav_menu(
              array (
                'menu' => 'main-menu',
                'container' => false, // parent container
                'depth' => 1,
                'items_wrap' => '%3$s', // removes ul
                'walker' => new Description_Walker // custom walker to replace li with div
              )
            );
            ?>
          </ul>

        </div>
      </div>
    </nav>
  </header>

<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <!-- <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">Register or Login to see direct contract jobs</h4>

      </div> -->
      <div class="modal-body">

        <div class="nav nav-tabs mb-3" id="pills-tab" role="tablist">
          <a class="nav-item nav-link" id="pills-register-tab" data-toggle="pill" href="#pills-register" role="tab" aria-controls="pills-register" aria-selected="true">Register</a>
          <a class="nav-item nav-link active" id="pills-login-tab" data-toggle="pill" href="#pills-login" role="tab" aria-controls="pills-login" aria-selected="false">Login</a>
        </div>


        <div class="tab-content" id="pills-tabContent">
          <div class="tab-pane fade" id="pills-register" role="tabpanel" aria-labelledby="pills-register-tab">
            <h4>Register to get access to direct jobs and use filters.</h4>

            <?php if ( is_active_sidebar( 'register-sidebar' ) ) : ?>
              <?php dynamic_sidebar( 'register-sidebar' ); ?>
            <?php endif; ?>
          </div>
          <div class="tab-pane fade show active" id="pills-login" role="tabpanel" aria-labelledby="pills-login-tab">
            <h4>You need to be logged in to see direct Contract Jobs or to use the filters.</h4>
            <?php wp_login_form(); ?>
          </div>
        </div>

      </div>

      <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">
          <!-- <span aria-hidden="true">&times;</span> -->

          <span aria-hidden="true">Close</span>
      </button>

      </div>


    </div>
  </div>
</div>
