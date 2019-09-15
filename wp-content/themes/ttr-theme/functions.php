<?php

// =====================
// Styles and Scripts
// =====================

function theme_styles() {
    wp_enqueue_style( 'google_web_fonts', 'https://fonts.googleapis.com/css?family=DM+Serif+Text|Quicksand&display=swap' );
    wp_enqueue_style( 'bootstrap_css', get_template_directory_uri() . '/assets/css/bootstrap.min.css' );
    // wp_enqueue_style( 'slick-slider-styles', get_stylesheet_directory_uri() . '/assets/css/slick.min.css' ); 
    // wp_enqueue_style( 'slick-slider-theme-styles', get_stylesheet_directory_uri() . '/assets/css/slick-theme.min.css' );  
    wp_enqueue_style( 'slick_css', '//cdn.jsdelivr.net/jquery.slick/1.5.0/slick.css' );
    wp_enqueue_style( 'bootstrap_css', get_template_directory_uri() . '/assets/css/fa-all.min.css' );
    
    wp_enqueue_style( 'style_css', get_stylesheet_uri());
}

add_action( 'wp_enqueue_scripts', 'theme_styles');

function my_theme_scripts() {
    wp_enqueue_script( 'jquery', get_template_directory_uri() . '/assets/js/vendor/jquery.min.js', NULL, '1.11.0', false);
    wp_enqueue_script( 'bootstrap_js', get_template_directory_uri() . '/assets/js/vendor/bootstrap.min.js', array('jquery'));
    wp_enqueue_script('slick-slider-js', get_stylesheet_directory_uri() . '/assets/js/vendor/slick.min.js', array('jquery'), '', true );
    // wp_enqueue_script( 'slick_js', '//cdn.jsdelivr.net/jquery.slick/1.5.0/slick.min.js', array('jquery'), '', true );
    wp_enqueue_script( 'my_custom_js', get_template_directory_uri() . '/assets/js/scripts.js', array('jquery', 'slick-slider-js'), '', true);
}

add_action( 'wp_enqueue_scripts', 'my_theme_scripts' );



// =====================
// Gravity forms
// =====================

function allow_basic_tags( $allowable_tags ) {
    return '<p><a><strong><em>';
}
add_filter( 'gform_allowable_tags_6', 'allow_basic_tags' );

function change_post_type( $post_data, $form, $entry ) {
    //only change post type on form id 5
    if ( $form['id'] != 1) { // 1 is the jobs post form
       return $post_data;
    }
 
    $post_data['post_type'] = 'jobs';
    return $post_data;
}
add_filter( 'gform_post_data', 'change_post_type', 10, 3 );


// Super hacky but does the job
// add_filter( 'gform_field_choice_markup_pre_render', function ( $choice_markup, $choice, $field, $value ) {
    
//     if ( $field->get_input_type() == 'radio') {

//         $new_string;
        
//         if ($choice['text'] === 'Premium') {
//             $new_string = sprintf( '>%s <div class="card"><div class="card-header"><h4 class="my-0 font-weight-normal">' . $choice['text'] . '</h4></div><div class="card-body"><h1 class="card-title pricing-card-title">£99 <small class="text-muted"></small></h1><ul class="list-unstyled mt-3 mb-4">  <li>Up to 45 day listing</li>  <li>Company logo</li>  <li>Primary position on our homepage</li>  <li>Promoted on our newsletter</li> <li>Promoted on Social media</li></ul><div data-type="5" class="btn btn-lg btn-block btn-primary">Post a ' . $choice['text'] . ' job</div></div></div> %s<', '', GFCommon::to_money( $choice['price'] ) );
//             return str_replace( ">{$choice['text']}<", $new_string, $choice_markup );
//         } elseif ($choice['text'] === 'Standard') {
//             $new_string = sprintf( '>%s <div class="card"><div class="card-header"><h4 class="my-0 font-weight-normal">' . $choice['text'] . '</h4></div><div class="card-body"><h1 class="card-title pricing-card-title">£49 <small class="text-muted"></small></h1><ul class="list-unstyled mt-3 mb-4">  <li>30 day listing</li>  <li>Company logo</li>  <li>Secondary position on our homepage</li>  <li>Promoted on Social media</li></ul><div data-type="5" class="btn btn-lg btn-block btn-primary">Post a ' . $choice['text'] . ' job</div></div></div> %s<', '', GFCommon::to_money( $choice['price'] ) );
//             return str_replace( ">{$choice['text']}<", $new_string, $choice_markup );
//         } elseif ($choice['text'] === 'Basic') {
//             $new_string = sprintf( '>%s <div class="card"><div class="card-header"><h4 class="my-0 font-weight-normal">' . $choice['text'] . '</h4></div><div class="card-body"><h1 class="card-title pricing-card-title">£9 <small class="text-muted"></small></h1><ul class="list-unstyled mt-3 mb-4">  <li>30 day listing</li>  <li>No company logo</li> </ul><div data-type="5" class="btn btn-lg btn-block btn-primary">Post a ' . $choice['text'] . ' job</div></div></div> %s<', '', GFCommon::to_money( $choice['price'] ) );
//             return str_replace( ">{$choice['text']}<", $new_string, $choice_markup );
//         }

//         return $choice_markup;
//     }
 
//     return $choice_markup;
// }, 10, 4 );

add_filter( 'gform_field_choice_markup_pre_render', function ( $choice_markup, $choice, $field, $value ) {
    
    if ( $field->get_input_type() == 'checkbox') {
        
        $new_string = sprintf( '>%s <div class="custom-checkbox">' . $choice['text'] . '</div> %s<', '', GFCommon::to_money( $choice['price'] ) );
        return str_replace( ">{$choice['text']}<", $new_string, $choice_markup );

        return $choice_markup;
    }
 
    return $choice_markup;
}, 10, 4 );



// =====================
// Filter
// =====================

/**
 * Filter the except length to 20 words.
 *
 * @param int $length Excerpt length.
 * @return int (Maybe) modified excerpt length.
 */
function wpdocs_custom_excerpt_length( $length ) {
    return $length;
}
add_filter( 'get_the_excerpt', 'wpdocs_custom_excerpt_length', 999 );


add_action('init', 'my_custom_init');
function my_custom_init() {
    add_post_type_support( 'page', 'excerpt' );
}


function theme_setup() {
    // Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );
	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
    add_theme_support( 'title-tag' );
    /*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	add_image_size( 'twentyseventeen-featured-image', 2000, 1200, true );

    add_image_size( 'twentyseventeen-thumbnail-avatar', 100, 100, true );
    
    /*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'comment-form',
		'comment-list',
		'gallery',
        'caption',
        'editor',
        'thumbnail',
        'excerpt'
    ) );
    
    /*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
		'gallery',
		'audio',
    ) );
    
    add_theme_support('post-thumbnails', array( 'jobs' ));
}
add_action( 'after_setup_theme', 'theme_setup' );

// ------------------------------------------------
// ---------- Add class to post excerpts ----------
// ------------------------------------------------
function add_excerpt_class( $excerpt ) {
    $excerpt = str_replace( "<p", "<p class=\"post__excerpt\"", $excerpt );
    return $excerpt;
}
add_filter( "the_excerpt", "add_excerpt_class" );

// Changing excerpt more
function new_excerpt_more($more) {
  global $post;
  remove_filter('excerpt_more', 'new_excerpt_more');
  return '...';
}
add_filter('excerpt_more','new_excerpt_more');

// --------------------------------------------
// ---------- Enable sidebar widgets ----------
// --------------------------------------------


function create_sidebar( $name, $id, $description ) {
    register_sidebar(array(
        'name' => __( $name ),
        'id' => $id,
        'description' => __( $description ),
        'before_widget' => '<div id="'.$id.'" class="widget %1$s %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));
  }
  create_sidebar( 'Login sidebar', 'login-sidebar', 'for the modal' );
  create_sidebar( 'Register sidebar', 'register-sidebar', 'for the modal' );
  //create_sidebar( 'Blog Sidebar', 'blog-sidebar', 'For blog pages' );




  // ------------------------------------------------
// ---------- Add post thumbnail support ----------
// ------------------------------------------------
add_theme_support('post-thumbnails');
add_image_size('featured-image', 520, 295);
add_image_size('large-thumbnail', 246, 140);



//Page Slug Body Class
function add_slug_body_class( $classes ) {
global $post;
if ( isset( $post ) ) {
$classes[] = $post->post_type . '-' . $post->post_name;
}
return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );


class Description_Walker extends Walker_Nav_Menu {
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 )
    {
        !empty ( $class_names ) and $class_names = ' class="'. esc_attr( $class_names ) . '"';
        $output .= '<li class="nav-item nav-item--' . $item->ID . '">';
        $attributes  = 'class="nav-link"';
        !empty( $item->attr_title ) and $attributes .= ' title="'  . esc_attr( $item->attr_title ) .'"';
        !empty( $item->target ) and $attributes .= ' target="' . esc_attr( $item->target     ) .'"';
        !empty( $item->xfn ) and $attributes .= ' rel="'    . esc_attr( $item->xfn        ) .'"';
        !empty( $item->url ) and $attributes .= ' href="'   . esc_attr( $item->url        ) .'"';
        $title = apply_filters( 'the_title', $item->title, $item->ID );
        $item_output = $args->before
        . "<a $attributes>"
        . $args->link_before
        . $title
        . '</a></li>'
        . $args->link_after
        . $args->after;
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}

add_action('nav_menu_css_class', 'add_current_nav_class', 10, 2 );
	
function add_current_nav_class($classes, $item) {
    
    // Getting the current post details
    global $post;
    
    // Getting the post type of the current post
    $current_post_type = get_post_type_object(get_post_type($post->ID));
    $current_post_type_slug = $current_post_type->rewrite[slug];
        
    // Getting the URL of the menu item
    $menu_slug = strtolower(trim($item->url));
    
    // If the menu item URL contains the current post types slug add the current-menu-item class
    if (strpos($menu_slug,$current_post_type_slug) !== false) {
    
       $classes[] = 'current-menu-item';
    
    }
    
    // Return the corrected set of classes to be added to the menu item
    return $classes;

}




function register_my_menus() {
    register_nav_menus(
      array(
        'main-menu' => __( 'Main Menu' ),
        'footer-menu' => __( 'Footer Menu' )
      )
    );
  }
  add_action( 'init', 'register_my_menus' );


// flush_rewrite_rules(true);


function sr_remove_cat_base( $link, $term, $taxonomy )
{
    // Name of your custom taxonomy ( category )
    if ( $taxonomy !== 'custom_tax' ) // Don't do anything if not our cpt taxonomy
        return $link;                 

    // Slug of your custom taxonomy ( category )
    return str_replace( 'custom-tax/', '', $link ); // Remove the base
}
add_filter( 'term_link', 'sr_remove_cat_base', 10, 3 );


function google_analytics() { ?>        
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-91449158-8"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-91449158-8');
</script>


<?php }
add_action( 'wp_head', 'google_analytics', 10 );




add_filter( 'gform_enable_password_field', '__return_true' );


// Once the user has registered we need to open the login modal so they can login and remove redirected query
// ?registered=true which opens the login modal
add_filter( 'login_redirect', function( $url, $query, $user ) {
    return home_url();
}, 10, 3 );








function my_custom_post_type_news() {
    $labels = array(
      'name'               => _x( 'News', 'post type general name' ),
      'singular_name'      => _x( 'News Item', 'post type singular name' ),
      'add_new'            => _x( 'Add New', 'book' ),
      'add_new_item'       => __( 'Add New News Item' ),
      'edit_item'          => __( 'Edit News Item' ),
      'new_item'           => __( 'New News Item' ),
      'all_items'          => __( 'All News' ),
      'view_item'          => __( 'View News Item' ),
      'search_items'       => __( 'Search News' ),
      'not_found'          => __( 'No news items found' ),
      'not_found_in_trash' => __( 'No news items found in the Trash' ),
      'menu_name'          => 'News'
    );
    $args = array(
      'labels'        => $labels,
      'description'   => 'Holds our products and product specific data',
      'public'        => true,
      'menu_position' => 5,
      'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
      'has_archive'   => true,
    );

    register_post_type( 'news', $args ); 
  }
  add_action( 'init', 'my_custom_post_type_news' );

  function custom_post_type_partnership() {
    $labels = array(
      'name'               => _x( 'Partnerships', 'post type general name' ),
      'singular_name'      => _x( 'Partnership', 'post type singular name' ),
      'add_new'            => _x( 'Add New', 'book' ),
      'add_new_item'       => __( 'Add New Partnership' ),
      'edit_item'          => __( 'Edit Partnership' ),
      'new_item'           => __( 'New Partnership' ),
      'all_items'          => __( 'All News' ),
      'view_item'          => __( 'View Partnership' ),
      'search_items'       => __( 'Search News' ),
      'not_found'          => __( 'No Partnerships found' ),
      'not_found_in_trash' => __( 'No Partnerships found in the Trash' ),
      'menu_name'          => 'Partnerships'
    );
    $args = array(
      'labels'        => $labels,
      'description'   => 'Holds our partnerships and product specific data',
      'public'        => true,
      'menu_position' => 5,
      'supports' => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
      'has_archive'  => true,
      'taxonomies' => array( 'category' ),
    );
    register_post_type( 'partnerships', $args ); 
  }
  add_action( 'init', 'custom_post_type_partnership' );


  function create_tag_taxonomies() 
{
  // Add new taxonomy, NOT hierarchical (like tags)
  $labels = array(
    'name' => _x( 'Tags', 'taxonomy general name' ),
    'singular_name' => _x( 'Tag', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Tags' ),
    'popular_items' => __( 'Popular Tags' ),
    'all_items' => __( 'All Tags' ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit Tag' ), 
    'update_item' => __( 'Update Tag' ),
    'add_new_item' => __( 'Add New Tag' ),
    'new_item_name' => __( 'New Tag Name' ),
    'separate_items_with_commas' => __( 'Separate tags with commas' ),
    'add_or_remove_items' => __( 'Add or remove tags' ),
    'choose_from_most_used' => __( 'Choose from the most used tags' ),
    'menu_name' => __( 'Tags' ),
  ); 

  register_taxonomy('tag','partnerships',array(
    'hierarchical' => false,
    'labels' => $labels,
    'show_ui' => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var' => true,
    'rewrite' => array( 'slug' => 'tag' ),
  ));
}

add_action( 'init', 'create_tag_taxonomies', 0 );

?>