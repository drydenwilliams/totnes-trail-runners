
<?php
  $lower_range = get_field( "lower_range" );
  $upper_range = get_field( "upper_range" );
  
  if ($lower_range !== '0') {
    echo '<strong>&#163;' . number_format($lower_range) . '</strong>';

    foreach (get_the_terms(get_the_ID(), 'job_type') as $cat) {
      if (!$upper_range) {
        if ($cat->name === 'Full-time') {
          echo ' a year';
        } elseif ($cat->name === 'Part-time') {
          echo ' an hour';
        } elseif ($cat->name === 'Intern-time') {
          echo ' an hour';
        } elseif ($cat->name === 'Contract') {
          echo ' a day';
        }
      }
    }
  }

  if (empty($upper_range) || $upper_range === '0') {
    echo '';
  } else {
    
    echo ' - <strong>&#163;' . number_format($upper_range) . '</strong>';

    foreach (get_the_terms(get_the_ID(), 'job_type') as $cat) {
      if ($cat->name === 'Full-time') {
        echo ' a year';
      } elseif ($cat->name === 'Part-time') {
        echo ' an hour';
      } elseif ($cat->name === 'Intern-time') {
        echo ' an hour';
      } elseif ($cat->name === 'Contract') {
        echo ' a day';
      }
    }
  }
?>