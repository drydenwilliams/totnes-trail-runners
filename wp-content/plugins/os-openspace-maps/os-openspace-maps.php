<?php  
/* 
Plugin Name: OS OpenSpace Maps
Plugin URI: https://skirridsystems.co.uk/wordpress-plugins/os-openspace-maps/
Description: Plugin for displaying OS OpenSpace Maps
Author: Simon Large, Jon Lynch
Version: 1.3.1
Author URI: https://skirridsystems.co.uk/
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Pull in the include files
require_once( plugin_dir_path( __FILE__ ) . 'osmap_generator.php' );
require_once( plugin_dir_path( __FILE__ ) . 'osmap_admin_options.php' );

// Set up all our hooks
define("OSMAP_VERSION", "1.3");     // set the version    
register_activation_hook( __FILE__, 'osmap_activate' ); // if the plugin has just been activated check for upgrade
add_action('admin_notices', 'osmap_admin_notice' );
add_filter('upload_mimes', 'osmap_mime_types', 1, 1);
add_action('admin_init', 'osmap_admin_init');
add_action('admin_menu', 'osmap_admin_menu');
add_action ('init', 'osmap_upgrade_options');
add_action ('init', 'osmap_register_scripts');
add_action('wp_enqueue_scripts', 'osmap_add_stylesheet');
add_action('wp_footer', 'osmap_print_scripts');
add_shortcode( 'osmap', 'osmap_generator');
add_action ('admin_head-post.php', 'osmap_add_help_tab_post_page'); // add the help tabs to the post editing page
add_action ('admin_head-post-new.php', 'osmap_add_help_tab_post_page');

// Link to the documentation page
function osmap_documentation_url() {
    return 'https://skirridsystems.co.uk/wordpress-plugins/os-openspace-maps/';
}

// Link to the API key registration URL
function osmap_registration_url() {
    return 'https://openspace.ordnancesurvey.co.uk/osmapapi/register.do';
}

// Link to OS support page
function osmap_os_support_url() {
    return 'https://www.ordnancesurvey.co.uk/business-and-government/help-and-support/web-services/os-openspace/os-openspace.html';
}

// Add a simple help tab to the Post and Page editors.
function osmap_add_help_tab_post_page() {
    $screen = get_current_screen();
    $type = $screen->post_type;
    if ( ( $type !='post' )  && ( $type != 'page' ) ) return;
    if ( ! method_exists( $screen, 'add_help_tab' ) ) return; //for versions of WP before 3.3 TODO add contextual help
    // Build the list of resolutions.
    foreach ( osmap_supported_scales() as $key => $value ) {
        if ( $key == 3 ) $key = 2.5;    // Show OS value for our internal '3'.
        $res_list .= '<li>' . $key . ' = ' . $value . '</li>';
    }
    $screen->add_help_tab( array(
        'id'      => 'osmap-help',
        'title'   => 'OS Maps',
        'content' => '<p>To add an OS OpenSpace map to your post or page use the shortcode <code>[osmap]</code>. The height and width can be set using <code>[osmap height="400" width ="500"]</code> where the dimensions are in pixels. Override the default map scale using <code>scale="5"</code>. Scale values can be selected from the following list.</p><ul>' . $res_list . '</ul><dl><dt>Markers</dt><dd>To add markers use the <em>markers</em> attribute. Add the marker location either as a 6, 8 or 10 figure grid reference or as a decimal lat and long. Each marker can be given a label by following the location with a semicolon and then adding the text. Separate each marker with a |. For example: <code>[osmap markers="NY21600720;Scafell Pike|NY18600880;Wasdale Head"]</code> will show two markers with the labels Wasdale and St Bees Head.</dd><dt>Tracks</dt><dd>To add tracks use the file uploader to upload kml or gpx files, then copy the address of the file. Then use the attribute <code>gpx="http://www.example.com/myfile.gpx"</code> or <code>kml="http://www.example.com/myfile.kml"</code> as appropriate. The tracks can be coloured using <code>colour="red"</code> or <code>colour="#ff0000"</code>. Set the track width using <code>track="2"</code></dd></dl><dt>Centre</dt><dd>If you want to centre the map manually or if you are using neither markers nor tracks, use centre="xxx" to specify the point the map is centred on.</dd><p>Default values for width, height, scale, colour and track can be set in the Admin Settings page, along with the list of resolutions allowed for all maps.</p>',
    ) );  
    do_action( 'osmap_post_page_help');
};

// Activate the plugin and check the API key.
function osmap_activate () {
    set_transient( 'osmap-activation-check', true, 5 );
}

function osmap_admin_notice() {
    if ( get_transient( 'osmap-activation-check' ) ) {
        $options = get_option( 'osmap_options' );
        $key = osmap_api_key_check( $options['apikey'] );
        if ( $key == 'none' ) {
            echo '<div class="error notice is-dismissible"><p><strong>You have not yet entered an API key.</strong> OS OpenSpace Maps will not be shown until one is entered. To get an API key click <a href="' . osmap_registration_url() . '" title="API key registration">here</a>.</p><p>If you are developing on <code>localhost</code> you can enter anything.</p><p>Go to the OpenSpace Maps <a href="' . osmap_admin_page_url() . '">Settings page</a> to enter the API key.</p></div>';
        } elseif ( $key != 'good' ) {
            echo '<div class="error notice is-dismissible"><p><strong>There appears to be an error with your API key.</strong> OS OpenSpace Maps will not be shown until it is corrected. To get an API key click <a href="' . osmap_registration_url() . '" title="API key registration">here</a>.</p><p>Further information is available in the <a href="' . osmap_os_support_url() . '">OpenSpace FAQ</a>.</p><p><strong>Please note that it can take up to 24 hours after creating or updating an API key before that key is recognised</strong>. Refer to the <a href="' . osmap_documentation_url() . '">plugin documentation</a> for more information.</p></div>';
        }

        delete_transient( 'osmap-activation-check' );
    }
}

// Set the URL scheme to match the scheme of this Wordpress installation.
// OS mapping service is very picky about this.
function osmap_set_url_scheme($url) {
    $scheme = is_ssl() ? 'https' : 'http';
    return set_url_scheme( $url, $scheme );
}

// Get the URL for the OS mapping script file, with matched URL scheme.
function osmap_get_script_url() {
    return osmap_set_url_scheme( 'https://openspace.ordnancesurvey.co.uk/osmapapi/openspace.js' );
}

// Register scripts.
function osmap_register_scripts() {
    $options = get_option('osmap_options');
    wp_register_script ("openspace",
        osmap_get_script_url() . '?key=' . esc_attr($options['apikey']),
        "",
        "1.0",
        false); 
    wp_register_script ('osmapsjs',
        plugins_url( 'osmapsjs.js', __FILE__ ),
        '',
        '1.1',
        true);
}

// Output scripts in the footer.
function osmap_print_scripts() {
    // Checks if they are needed
    global $osmap_add_scripts;
    if ( ! $osmap_add_scripts )
        return; 
    wp_print_scripts('openspace');
    wp_print_scripts('osmapsjs');?>
    <script type="text/javascript">
<?php
    foreach ( $osmap_add_scripts as $mapno ) {
        echo "if(typeof initmap". $mapno . " == 'function') initmap". $mapno . "();";
    }
    ?>
  </script>
<?php
}

// Enqueue stylesheet, if it exists.
function osmap_add_stylesheet() {
    if ( file_exists( plugin_dir_path( __FILE__ ) . 'style.css' ) ) {
        wp_register_style( 'osmap_stylesheet', plugins_url( 'style.css', __FILE__ ) );
        wp_enqueue_style( 'osmap_stylesheet' );
    }
}

// Set up the MIME types for GPX and KML files to permit upload.
function osmap_mime_types($mime_types){
    $mime_types['gpx'] = 'text/xml'; // Adding gpx extension
    $mime_types['kml'] = 'text/xml'; // Adding kml extension
    return $mime_types;
}

// Check for a valid API key, returns none, error, good or bad
function osmap_api_key_check( $key ) {
    // if there is no API key
    if ( ! $key ) {
        return 'none';
    }
    // There is a key we now need to check it
    $args = array();
    $args[timeout]=15;
    $result = wp_remote_get( osmap_get_script_url() . '?key=' . esc_attr($key), $args );
    if( is_wp_error( $result ) )
        return 'error';
    if ( $result['response']['code'] != 200 )
        return 'bad';
    if ( stripos( wp_remote_retrieve_body( $result ), 'API Key not valid' ) !== false )
        return 'bad';
    return 'good';
}
