<?php
/* OS OpenSpace Maps
 * Show the admin settings for os-openspace-maps plugin.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function osmap_upgrade_options() {
    // First make sure our options are stored in array form, not object form.
    // This was done historically but I can't find a way to make that work in the Settings API.
    // This would also be the place to upgrade the options, delete old ones, add new ones.
    $options = get_option('osmap_options');
    if ( is_object( $options ) ) {
        update_option( 'osmap_options', get_object_vars( $options ) );
    }
}

// Return the URL of this settings page.
function osmap_admin_page_url() {
    return admin_url( 'options-general.php?page=' . 'OpenSpace_Maps' );
}

// Add help tabs to the settings page
function osmap_add_help_tab_admin() {
    $screen = get_current_screen();
    if ( ! method_exists( $screen, 'add_help_tab' ) ) return;
    $screen->add_help_tab( array(
        'id'      => 'overall-help',
        'title'   => 'Overview',
        'content' => '<p>This is where you set the options for using the OS Openspace Maps plugin.</p>',
    ) );  
    $screen->add_help_tab( array(
		    'id'      => 'api-key',
		    'title'   => 'API keys',
		    'content' => '<p>In order to use this plugin you need to register with the Ordnance Survey for an API key. Your key will be linked to your domain name and there will be a limit to the number of map tiles served to your API key each day. If you wish to use OpenSpace on multiple domains you can add them to your key.</p><p>Further information can he found in the <a href="' . osmap_documentation_url() . '">plugin documentation</a>.</p>',
    ) );  
    $screen->add_help_tab( array(
		    'id'      => 'map-defaults',
		    'title'   => 'Map Defaults',
		    'content' => '<dl><dt>Map Scale</dt><dd>Set the default scale to be used when showing a single marker. When a KML or GPX file is being used or multiple markers are shown, the map will automatically zoom to fit, ignoring the selected zoom level.</dd><dt>Map Height</dt><dd>Set the default height of the map in pixels.</dd><dt>Map Width</dt><dd>Set the default width of the map in pixels or leave blank to use all available width in that section of the page.</dd><dt>Track Colour</dt><dd>Set the colour used for plotting GPX or KML tracks, either using standard HTML colour names eg <em>red</em> or using hex RGB codes eg <em>#ff0000</em>. If unspecified, the track will be drawn in red.</dd><dt>Track Width</dt><dd>Set the default track width in pixels. If unspecified, a value of 3 will be used.</dd></dl>',
    ) );  
    $screen->add_help_tab( array(
	      'id'      => 'resolutions',
	      'title'   => 'Map Scales',
	      'content' => '<p>Select all the scales you would like from the list. <strong>This applies to all maps</strong>.</p>',
    ) );
    $screen->add_help_tab( array(
	      'id'      => 'others',
	      'title'   => 'Other Settings',
	      'content' => '<dl><dt>Disable mousewheel zoom</dt><dd>By default the mousewheel will change the zoom level of the map when the mouse is inside the map area. This can lead to unexpected zoom when using the mousewheel to scroll down the page. Check this box to prevent the mousewheel from zooming. <strong>This applies to all maps</strong>.</dd><dt>Add link to GPX/KML files below map</dt><dd>This will add a link below the map, allowing users to download the associated GPX or KML file. The link is styled with class="osmap_download". <strong>This applies to all maps</strong>.</dd></dl>',
    ) );    //TODO add more help
    do_action( 'osmap_settings_help');
}

// Function to return an array of all supported map scales indexed by scale reference used in options.
function osmap_supported_scales() {
    $scales = array(
        2500 => 'Whole UK',
        1000 => 'Whole UK zoomed in a bit',
        500  => 'Whole UK zoomed in a bit more',
        200  => '1:1M Overview resampled',
        100  => '1:1M Overview',
        50   => '1:250,000 resampled',
        25   => '1:250,000',
        10   => '1:50,000 Landranger resampled',
        5    => '1:50,000 Landranger',
        4    => '1:25,000 VectorMap District resampled',
        3    => '1:25,000 VectorMap District',
        2    => '1:10,000 Street View resampled',
        1    => '1:10,000 Street View'
    );
    return $scales;
}

// Return an array containing only the user-selected scales
function osmap_selected_scales($options, $scale = '') {
    $selected = array();
    foreach ( osmap_supported_scales() as $key => $value ) {
        $opt_key = 'res_' . $key;
        if ( $options[$opt_key] || ( $key == $scale ) )
            $selected[$key] = $value;
    }
    return $selected;
}

// Convert scale reference to zoom value (index within selected list)
function osmap_scale_to_zoom($options, $scale) {
    $zoom = 0;
    foreach ( osmap_selected_scales( $options, $scale ) as $key => $value ) {
        if ($key == $scale) return $zoom;
        ++$zoom;
    }
    return 0;    // Default first selected
}

// Convert zoom value to a scale reference
function osmap_zoom_to_scale($options, $zoom) {
    $index = 0;
    foreach ( osmap_selected_scales( $options ) as $key => $value ) {
        if ($index == $zoom) return $key;
        ++$index;
    }
    return 5;    // Default 1:50,000
}

// Initialisation function, called by add_action.
function osmap_admin_init() {
    // Register the name used for our options in the database.
    register_setting('os-openspace-maps', 'osmap_options', 'osmap_sanitize_cb');
    // Add the sections to be used on the page
    add_settings_section('sect_api_key', 'API Keys', '', 'os-openspace-maps');
    add_settings_section('sect_defaults', 'Map Defaults', 'osmap_default_section_cb', 'os-openspace-maps');
    add_settings_section('sect_scales', 'Map Scales', 'osmap_scale_section_cb', 'os-openspace-maps');
    add_settings_section('sect_misc', 'Other Settings', '', 'os-openspace-maps');
    // Add fields to each section
    add_settings_field('osmap_api_key', 'OpenSpace API Key', 'osmap_api_cb', 'os-openspace-maps', 'sect_api_key');
    add_settings_field('default_scale', 'Map Scale', 'osmap_default_scale_cb', 'os-openspace-maps', 'sect_defaults');
    add_settings_field('default_height', 'Map Height (pixels)', 'osmap_default_height_cb', 'os-openspace-maps', 'sect_defaults');
    add_settings_field('default_width', 'Map Width (pixels)', 'osmap_default_width_cb', 'os-openspace-maps', 'sect_defaults');
    add_settings_field('default_colour', 'Track Colour', 'osmap_default_colour_cb', 'os-openspace-maps', 'sect_defaults');
    add_settings_field('default_track', 'Track Width (pixels)', 'osmap_default_track_cb', 'os-openspace-maps', 'sect_defaults');
    add_settings_field('map_scales', 'Scales to Use', 'osmap_map_scales_cb', 'os-openspace-maps', 'sect_scales');
    add_settings_field('mousewheel', 'Disable Mousewheel Zoom', 'osmap_mousewheel_cb', 'os-openspace-maps', 'sect_misc');
    add_settings_field('add_link', 'Add Link to GPX/KML Files', 'osmap_add_link_cb', 'os-openspace-maps', 'sect_misc');
}

// Add settings page to the menu, called by add_action.
function osmap_admin_menu() {
    // Add our settings page to the options menu
    $osmap_admin_page = add_options_page('OpenSpace Maps', 'OpenSpace Maps', 'manage_options', 'os-openspace-maps', 'osmap_options_display'); 
    // Add help tab to our settings page
    add_action('load-'.$osmap_admin_page, 'osmap_add_help_tab_admin');    
}

// Callback functions used for the Settings API
// Callbacks to generate section headings.
function osmap_default_section_cb() {
    echo '<p>These options apply to all maps but can be overridden using shortcode attributes.</p>';
}

function osmap_scale_section_cb() {
    echo '<p>Select all the map scales you want to use. Maps contain a zoom control which will use only these selected scales. <strong>This selection applies to all maps.</strong></p>';
}

// Callbacks to display each of the settings.
function osmap_api_cb() {
    $options = get_option('osmap_options');
    echo '<input id="osmap_api_key" name="osmap_options[apikey]" size="40" type="text" value="' . esc_attr($options['apikey']) . '" />';
    echo '<p class="description">You must register with the Ordnance Survey for an API key <a href="' . osmap_registration_url() . '">here</a> before using this plugin.</p>';
}

function osmap_default_scale_cb() {
    $options = get_option('osmap_options');
    $scale = $options['default_scale'];
    if ( ! $scale ) {
        $scale = osmap_zoom_to_scale( $options, $options['default_zoom'] );
    }
    echo '<select id="default_scale" name="osmap_options[default_scale]">';
    foreach ( osmap_supported_scales() as $key => $value ) {
        $selected = ( $scale == $key ) ? 'selected="selected"' : '';
        echo '<option value="' . esc_attr($key) . '" ' . $selected . '>' . esc_attr($value) . '</option>';
    }
    echo '</select>';
}

function osmap_default_height_cb() {
    $options = get_option('osmap_options');
    echo '<input id="default_height" name="osmap_options[default_height]" size="4" type="text" value="' . esc_attr($options['default_height']) . '" />';
}

function osmap_default_width_cb() {
    $options = get_option('osmap_options');
    echo '<input id="default_width" name="osmap_options[default_width]" size="4" type="text" value="' . esc_attr($options['default_width']) . '" />';
    echo '<p class="description">Leave blank to use available width';
}

function osmap_default_colour_cb() {
    $options = get_option('osmap_options');
    echo '<input id="default_color" name="osmap_options[default_color]" size="12" type="text" value="' . esc_attr($options['default_color']) . '" />';
    echo '<p class="description">Named or hex colours, eg red or #ff0000';
}

function osmap_default_track_cb() {
    $options = get_option('osmap_options');
    echo '<input id="default_track" name="osmap_options[default_track]" size="4" type="text" value="' . esc_attr($options['default_track']) . '" />';
}

function osmap_map_scales_cb() {
    $options = (array) get_option('osmap_options');
    foreach ( osmap_supported_scales() as $key => $value ) {
        $opt_key = 'res_' . $key;
        echo '<input id="' . $opt_key . '" name="osmap_options[' . $opt_key . ']" type="checkbox"' . ($options[$opt_key] ? ' checked="true"' : '') . ' /> ' . $value . '<br />';
    }
}

function osmap_mousewheel_cb() {
    $options = get_option('osmap_options');
    echo '<input id="mousewheel" name="osmap_options[mousewheel]" type="checkbox"' . (($options['mousewheel'] == 'disabled') ? ' checked="true"' : '') . ' />';
}

function osmap_add_link_cb() {
    $options = get_option('osmap_options');
    echo '<input id="add_link" name="osmap_options[add_link]" type="checkbox"' . ($options['add_link'] ? ' checked="true"' : '') . ' />';
    echo '<p class="description">Adds a link to the GPX/KML file below the map if one has been used.</p>';
}

// Validate a simple integer and check range.
function osmap_validate_as_int( $text, $minval, $maxval, $blank_ok )
{
	  if ( $text === '' ) return $blank_ok;
	  $intval = filter_var( $text, FILTER_VALIDATE_INT );

	  if ( $intval === false || $intval < $minval || $intval > $maxval ) return false;

	  return true;
}

// Validate a named or hex HTML colour.
function osmap_validate_html_color($color, $blank_ok) {
	  $named = array('aliceblue', 'antiquewhite', 'aqua', 'aquamarine', 'azure', 'beige', 'bisque', 'black', 'blanchedalmond', 'blue', 'blueviolet', 'brown', 'burlywood', 'cadetblue', 'chartreuse', 'chocolate', 'coral', 'cornflowerblue', 'cornsilk', 'crimson', 'cyan', 'darkblue', 'darkcyan', 'darkgoldenrod', 'darkgray', 'darkgreen', 'darkkhaki', 'darkmagenta', 'darkolivegreen', 'darkorange', 'darkorchid', 'darkred', 'darksalmon', 'darkseagreen', 'darkslateblue', 'darkslategray', 'darkturquoise', 'darkviolet', 'deeppink', 'deepskyblue', 'dimgray', 'dodgerblue', 'firebrick', 'floralwhite', 'forestgreen', 'fuchsia', 'gainsboro', 'ghostwhite', 'gold', 'goldenrod', 'gray', 'green', 'greenyellow', 'honeydew', 'hotpink', 'indianred', 'indigo', 'ivory', 'khaki', 'lavender', 'lavenderblush', 'lawngreen', 'lemonchiffon', 'lightblue', 'lightcoral', 'lightcyan', 'lightgoldenrodyellow', 'lightgreen', 'lightgrey', 'lightpink', 'lightsalmon', 'lightseagreen', 'lightskyblue', 'lightslategray', 'lightsteelblue', 'lightyellow', 'lime', 'limegreen', 'linen', 'magenta', 'maroon', 'mediumaquamarine', 'mediumblue', 'mediumorchid', 'mediumpurple', 'mediumseagreen', 'mediumslateblue', 'mediumspringgreen', 'mediumturquoise', 'mediumvioletred', 'midnightblue', 'mintcream', 'mistyrose', 'moccasin', 'navajowhite', 'navy', 'oldlace', 'olive', 'olivedrab', 'orange', 'orangered', 'orchid', 'palegoldenrod', 'palegreen', 'paleturquoise', 'palevioletred', 'papayawhip', 'peachpuff', 'peru', 'pink', 'plum', 'powderblue', 'purple', 'red', 'rosybrown', 'royalblue', 'saddlebrown', 'salmon', 'sandybrown', 'seagreen', 'seashell', 'sienna', 'silver', 'skyblue', 'slateblue', 'slategray', 'snow', 'springgreen', 'steelblue', 'tan', 'teal', 'thistle', 'tomato', 'turquoise', 'violet', 'wheat', 'white', 'whitesmoke', 'yellow', 'yellowgreen');
	 
	  if ( $color === '' ) return $blank_ok;
	  if ( in_array( strtolower( $color ), $named ) ) return true;
	  return sanitize_hex_color( $color ) != NULL;
}

// Callback to sanitize the values entered.
function osmap_sanitize_cb($options) {
    $options['apikey'] = sanitize_text_field( trim( $options['apikey'] ) );
    $options['default_scale'] = sanitize_text_field( trim( $options['default_scale'] ) );
    $options['default_height'] = sanitize_text_field( trim( $options['default_height'] ) );
    $options['default_width'] = sanitize_text_field( trim( $options['default_width'] ) );
    $options['default_color'] = sanitize_text_field( trim( $options['default_color'] ) );
    $options['default_track'] = sanitize_text_field( trim( $options['default_track'] ) );
    $options['mousewheel'] = $options['mousewheel'] ? 'disabled' : 'enabled';
    $options['add_link'] = $options['add_link'] ? true : false;
    // Sanitize map scales and add the default if not already included.
    foreach ( osmap_supported_scales() as $key => $value ) {
        $opt_key = 'res_' . $key;
        $options[$opt_key] = ( $options[$opt_key] || ($options['default_scale'] == $key) ) ? true : false;
    }
    // Convert default_scale back to a zoom value.
    $options['default_zoom'] = osmap_scale_to_zoom($options, $options['default_scale']);
    
    $options = apply_filters('osmap_read_options', $options);
    $options['version'] = OSMAP_VERSION;

    // Now check for errors.
    // Check the API key.
    $options['apikey_state'] = osmap_api_key_check( $options['apikey'] );
    if ( $options['apikey_state'] == 'bad') {
        add_settings_error( 'osmap_messages', 'osmap_msg',
            '<p><strong>There appears to be an error with your API key.</strong> OS OpenSpace Maps will not be shown until it is corrected. To get an API key click <a href="' . osmap_registration_url() . '" title="API key registration">here</a>.</p><p>Further information is available in the <a href="' . osmap_os_support_url() . '">OpenSpace FAQ</a>.</p><p><strong>Please note that it can take up to 24 hours after creating or updating an API key before that key is recognised</strong>. Refer to the <a href="' . osmap_documentation_url() . '">plugin documentation</a> for more information.</p>',
            'error' );
    } else if ( $options['apikey_state'] == 'none' ) {
        add_settings_error( 'osmap_messages', 'osmap_msg',
            '<p><strong>You have not entered an API key.</strong> OS OpenSpace Maps will not be shown until one is entered. To get an API key click <a href="' . osmap_registration_url() . '" title="API key registration">here</a>.</p><p>If you are developing on <code>localhost</code> you can enter anything.</p>',
            'error' );
    }    
    if ( ! osmap_validate_as_int ($options['default_height'], 10, 30000, false ) ) {
        add_settings_error( 'osmap_messages', 'osmap_msg', '<p><strong>Default height is not a valid integer</strong> Must lie in the range 10 - 30,000</p>', 'error' );
    }
    if ( ! osmap_validate_as_int ($options['default_width'], 10, 10000, true ) ) {
        add_settings_error( 'osmap_messages', 'osmap_msg', '<p><strong>Default width is not a valid integer</strong> Must lie in the range 10 - 10,000 or left blank</p>', 'error' );
    }
    if ( ! osmap_validate_html_color ($options['default_color'], true ) ) {
        add_settings_error( 'osmap_messages', 'osmap_msg', '<p><strong>Default track colour is invalid</strong> Must be an HTML named color or #hex-color-value eg. #884488 or left blank</p>', 'error' );
    }
    if ( ! osmap_validate_as_int ($options['default_track'], 1, 10, true ) ) {
        add_settings_error( 'osmap_messages', 'osmap_msg', '<p><strong>Default GPX/KML track width is not a valid integer</strong> Must lie in the range 1 - 10 or left blank</p>', 'error' );
    }

    return $options;
}

// This function is what displays the settings page.
function osmap_options_display() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
 
    // Show the settings page form
    $title = "OS OpenSpace Maps Settings";
    $title = apply_filters( 'osmap_settings_title', $title );
?>
    <div class="wrap">
    <h2><?php echo esc_html($title);?></h2>
    <div style="float:right;margin-left:3em">
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="hidden" name="hosted_button_id" value="ZFCAZLGF7UFYS">
    <input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal — The safer, easier way to pay online.">
    <img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
    </form>
    </div>
    <form action="options.php" method="post">
<?php
        // Output security fields for the registered setting "osmap_options"
        settings_fields( 'os-openspace-maps' );
        // Output setting sections and their fields
        do_settings_sections('os-openspace-maps');
        // Output save settings button
        submit_button( 'Save Settings' );
?>
    </form>
    </div>
<?php
}
