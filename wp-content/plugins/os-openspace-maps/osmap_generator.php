<?php  
/* OS OpenSpace Maps
 * Convert the shortcode to HTML/JS to generate the map.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Function that is run when the shortcode is found
function osmap_generator( $atts ) {
    // Do nothing if we are on a feed
    if ( is_feed() ) return;
    // Get the options set on admin page
    $options = get_option('osmap_options');
    // Return if the api key is not valid
    if ( ! ( 'good' == $options['apikey_state'] ) )
        return '<div id="os-map-error">OpenSpace Maps Error: Invalid API Key</div>';
    global $osmap_add_scripts;
    $mapid = count( $osmap_add_scripts );
    $osmap_add_scripts[] = $mapid;
    $apikey = $options['apikey'];
    $default_zoom = $options['default_zoom'];
    $default_height = $options['default_height'];
    $default_width = $options['default_width'];
    $default_color = $options['default_color'];
    $default_track = $options['default_track'];
    // Get the shortcode info, set defaults
    extract (shortcode_atts( array(
      'grid'   => '',
      'zoom'   => $default_zoom,
      'scale'  => '',
      'label'  => '',
      'height' => $default_height,
      'width'  => $default_width,
      'centre' => '',
      'color'  => $default_color,
      'colour' => '',
      'track'  => $default_track,
      'll'     => '',
      'gpx'    => '',
      'kml'    => '',
      'markers'=> '',
      'markerfile'=> '',
    ), $atts ) );
    // Use colour in preference to color
    if ( $colour ) $color = $colour;
    // Set default track colour and width if unset
    if ( $color == '' ) $color = 'red';
    if ( $track == '' ) $track = '3';
    // Use scale in preference to zoom
    if ( $scale ) {
        // Map OS 2.5 to the 3, which is what we use internally.
        if ( $scale == '2.5' ) $scale = '3';
        $zoom = osmap_scale_to_zoom( $options, $scale );
    }
    // Set point radius and opacity (not customizable at the moment)
    $opacity = '0.7';
    $radius = '3';
    // Explode the markers into an array
    $markers = explode ( '|', $markers );
    // For backward compatibility add in grid marker.
    if ( $grid != '' ) {
        $markers[0] = $grid . ';' . $label;
    }  
    if (''!=$width) 
        $width_css=' width: ' . $width . 'px;';
    else 
        $width_css='';
    $javascript = '
    <script type="text/javascript">
    // <![CDATA[
    function initmap' . $mapid . '() {
    // Initialize the map
    var options = {resolutions: [';
    $res_list = '';
    foreach ( osmap_selected_scales($options, $scale) as $key => $value ) {
        if ( $res_list != '' ) $res_list .= ',';
        $res_list .= (($key == 3) ? '2.5' : $key);
    }
    $javascript .= $res_list . ']};
    osMap' . $mapid . ' = new OpenSpace.Map(\'singlemap-' . $mapid . '\', options);';
    $download_link = '';
    if ( '' != $gpx ) {
        $download_link = osmap_set_url_scheme($gpx);
        $download_type = 'GPX';
        $javascript .= '
        var gpxLoaded = function(event) {
            // \'this\' is layer
            this.map.zoomToExtent(this.getDataExtent());
          }
        osMap' . $mapid .'.addLayer(new OpenLayers.Layer.Vector("GPX Vectors", {
          style: {
            strokeColor: "' . esc_js($color) . '",
            strokeWidth: "' . esc_js($track) . '",
            strokeOpacity: "' . esc_js($opacity) . '",
            pointRadius: "' . esc_js($radius) . '",
            fillColor: "' . esc_js($color) . '"
          },
          eventListeners: {
            "featuresadded": gpxLoaded
          },
          strategies: [new OpenLayers.Strategy.Fixed()],
          protocol: new OpenLayers.Protocol.HTTP({
            url: "' . esc_js($download_link) . '",
            format: new OpenLayers.Format.GPX({
              internalProjection: new OpenLayers.Projection("EPSG:27700"),
              externalProjection: new OpenLayers.Projection("EPSG:4326")
            })
          })
        }));';
    } elseif ( '' != $kml ) {
      $download_link = osmap_set_url_scheme($kml);
      $download_type = 'KML';
      $javascript .= '
      var gpxLoaded = function(event) {
          // \'this\' is layer
          this.map.zoomToExtent(this.getDataExtent());
        }
      osMap' . $mapid .'.addLayer(new OpenLayers.Layer.Vector("KML Vectors", {
        style: {
          strokeColor: "' . esc_js($color) . '",
          strokeWidth: "' . esc_js($track) . '",
          strokeOpacity: "' . esc_js($opacity) . '",
          pointRadius: "' . esc_js($radius) . '",
          fillColor: "' . esc_js($color) . '"
        },
        eventListeners: {
          "featuresadded": gpxLoaded
        },
        strategies: [new OpenLayers.Strategy.Fixed()],
        protocol: new OpenLayers.Protocol.HTTP({
          url: "' . esc_js($download_link) . '",
          format: new OpenLayers.Format.KML({
            internalProjection: new OpenLayers.Projection("EPSG:27700"),
            externalProjection: new OpenLayers.Projection("EPSG:4326")
          })
        })
      }));';
    } else if ( $ll != '' ) {
        // For backward compatibility allow lonlat and separate label.
        $javascript .= '
        var gridProjection = new OpenSpace.GridProjection();
        var lonlat = new OpenLayers.LonLat(' . esc_js($ll) .');
        var mapPoint = gridProjection.getMapPointFromLonLat(lonlat);
        osMap' . $mapid . '.setCenter( mapPoint, ' . esc_js($zoom) . ');
        var markers = new OpenLayers.Layer.Markers("Markers");
        osMap' . $mapid . '.addLayer(markers);
        //add a single marker containing information on the current post
        var content = "' . esc_js($label) . '";
        var marker = osMap' . $mapid . '.createMarker(mapPoint, null, content);
        markers.addMarker(marker); ';
    } else {
        // Markers file overrides attribute markers.
        if ( $markerfile != '' ) {
            $markers = file( $markerfile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
        }
        // Array of 1 or more markers.
        $auto_fit = false;
        if ( $markers[0] != '' ) {
            if ( count( $markers ) > 1 ) {
                // For multiple markers, zoom to extents unless explicit zoom and centre set.
                if ( $centre == '' || $zoom == '' ) $auto_fit = true;
            }
            if ( $centre == '' ) {
                // Use single marker as centre unless already specified.
                $centre = explode( ';', $markers[0]);
                $centre = $centre[0];
            }
            // Whether or not we use auto-fit later, we need to set centre and zoom before displaying markers.
            $javascript .= '
            osMap' . $mapid . '.setCenter( ConvertCoordinates ("' . esc_js($centre) . '"), ' . esc_js($zoom) . ');';
            $javascript .= '
            var markers = new OpenLayers.Layer.Markers("Markers");
            osMap' . $mapid . '.addLayer(markers);
            ';
           
            foreach ( $markers as $marker ) {
                list( $position, $text ) = explode( ';', $marker, 2 );
                // If the text may contain HTML, first sanitize it as if it were post content.
                if ( strpbrk( $text, "<>" ) ) {
                    $text = addslashes( wp_kses_post( $text ) );
                } else {
                    $text = esc_js( $text );
                }
                $javascript .= '
                var pos = ConvertCoordinates ("' . esc_js($position) . '"); 
                var content = "' . $text . '";
                var marker = osMap' . $mapid . '.createMarker(pos, null, content);
                markers.addMarker(marker); 
                ';
            }
        }
        if ( $auto_fit ) {
            $javascript .='
            osMap' . $mapid . '.zoomToExtent(osMap' . $mapid . '.getMarkerLayer().getDataExtent());';
        } else {
            $javascript .= '
            osMap' . $mapid . '.setCenter( ConvertCoordinates ("' . esc_js($centre) . '"), ' . esc_js($zoom) . ');';
        }
    }
    // Disable mousewheel zoom on request
    if ( 'disabled' == $options['mousewheel'] ) {
        $javascript .= '
        for( var c in osMap' . $mapid . '.controls )
          if( osMap' . $mapid . '.controls[c].CLASS_NAME == "OpenLayers.Control.Navigation" )
          {
            osMap' . $mapid . '.controls[c].disableZoomWheel();
          }';
    }
    // Disable keyboard scrolling when not over the map
    $javascript .= '
    for( var c in osMap' . $mapid . '.controls )
      if( osMap' . $mapid . '.controls[c].CLASS_NAME == "OpenLayers.Control.KeyboardDefaults" )
      {
        keys = osMap' . $mapid . '.controls[c];
        osMap' . $mapid . '.events.register( \'mouseover\', this, function(){ keys.activate(); } );
        osMap' . $mapid . '.events.register( \'mouseout\', this, function(){ keys.deactivate(); } );
        keys.deactivate();
      }
    }
    // ]]> 
  </script>';


    $javascript .= '<div class="osmap" id="singlemap-' . $mapid . '" style="display:none; height:' . esc_js($height) . 'px; max-width:100%;' . esc_js($width_css) . '"></div><div id="noscriptmap-' . $mapid . '"><h5>To see this map cookies and javascript must be enabled. If you are still having trouble after having checked both of these please contact us using the link at the top of the page</h5></div><script type="text/javascript">
    document.getElementById("singlemap-' . $mapid . '").style.display = "block";
    document.getElementById("noscriptmap-' . $mapid . '").style.display = "none"' . '</script><div id="spacer"></div>';
    if ( $options['add_link'] && ( $download_link != '' ) ) {
        $javascript .= '<a class="osmap_download" href="' . esc_url($download_link) . '" title="Right click and choose save-as if the direct click does not work.">Download ' . $download_type . ' file for GPS</a>';
    }
    $javascript = apply_filters ('osmap_output', $javascript, $atts, $mapid  );
    return $javascript; 
}
