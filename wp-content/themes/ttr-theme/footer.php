

<footer class="site-footer">
<div class="container-fluid">
<div class="row">
        <div class="col-12 col-md contact">
          <!-- <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block mb-2"><circle cx="12" cy="12" r="10"></circle><line x1="14.31" y1="8" x2="20.05" y2="17.94"></line><line x1="9.69" y1="8" x2="21.17" y2="8"></line><line x1="7.38" y1="12" x2="13.12" y2="2.06"></line><line x1="9.69" y1="16" x2="3.95" y2="6.06"></line><line x1="14.31" y1="16" x2="2.83" y2="16"></line><line x1="16.62" y1="12" x2="10.88" y2="21.94"></line></svg> -->
          Contact us:
          <div>
            Email
            <a class="text-muted" href="mailto:info@developerjobsboard.co.uk?">info@developerjobsboard.co.uk</a>
          </div>
          <div>
          Twitter
            <a class="text-muted" href="https://twitter.com/devjobsboard_" target="_blank">@DevJobsBoard_</a>
</div>
        </div>

        <div class="col-6 col-md">
          <h5>Helpful Resources</h5>
          <ul class="list-unstyled text-small">

          <li>
            <?php if ( is_user_logged_in() ) : ?>
              <a class="text-muted" href="<?php echo wp_logout_url(); ?>">Logout</a>
            <?php else : ?>
              <a class="text-muted" data-toggle="modal" data-target="#loginModal">Login</a>
            <?php endif; ?>
          </li>
            <li><a class="text-muted" href="<?php echo site_url(); ?>/our-story">Our story</a></li>
            <li><a class="text-muted" href="<?php echo site_url(); ?>/faqs">FAQ</a></li>
          </ul>
        </div>
        <!-- <div class="col-6 col-md">
          <h5>Quick search</h5>
          <ul class="list-unstyled text-small">
            <li><a class="text-muted" href="<?php echo site_url(); ?>/category/javascript">JavaScript</a></li>
            <li><a class="text-muted" href="<?php echo site_url(); ?>/skills/react">React</a></li>
            <li><a class="text-muted" href="<?php echo site_url(); ?>/skills/html/">HTML</a></li>
            <li><a class="text-muted" href="<?php echo site_url(); ?>/category/python">Python</a></li>
          </ul>
        </div> -->
        <div class="col-6 col-md">
          <h5>Sponsored By</h5>
          <ul class="list-unstyled text-small">
            <li><a class="text-muted" href="mailto:info@developerjobsboard.co.uk?subject=Can we sponsor you?">Sponsor us</a></li>
          </ul>
        </div>


      </div>

      <div class="row">
  <div class="col-md-12">
  Site developed by <a href="//www.fika.studio/" target="_blank">Fika Studio</a>
</div>
</div>

</div>
</footer>

  <?php wp_footer(); ?>



</body>

</html>
