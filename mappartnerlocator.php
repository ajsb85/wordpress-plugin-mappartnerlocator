<?php
/**
 * Plugin Name: Map Partner Locator
 * Plugin URI:  http://github.com/ajsb85/mappartnerlocator
 * Description: MapPartnerLocator - Wordpress Plugin
 * Version:     0.1.0
 * Author:      Alexander J. Salas B.
 * Author URI:  http://ajsb85.com
 * Donate link: http://ajsb85.com
 * License:     GPLv3
 * Text Domain: mappartnerlocator
 * Domain Path: /languages
 */

/**
 * Copyright (c) 2016 Alexander J. Salas B. (email : a.salas@ieee.org)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * Built using generator-plugin-wp
 */


/**
 * Autoloads files with classes when needed
 *
 * @since  NEXT
 * @param  string $class_name Name of the class being requested.
 * @return void
 */
function mappartnerlocator_autoload_classes( $class_name ) {
	if ( 0 !== strpos( $class_name, 'MPL_' ) ) {
		return;
	}

	$filename = strtolower( str_replace(
		'_', '-',
		substr( $class_name, strlen( 'MPL_' ) )
	) );

	MapPartnerLocator::include_file( $filename );
}
spl_autoload_register( 'mappartnerlocator_autoload_classes' );


/**
 * Main initiation class
 *
 * @since  NEXT
 * @var  string $version  Plugin version
 * @var  string $basename Plugin basename
 * @var  string $url      Plugin URL
 * @var  string $path     Plugin Path
 */
class MapPartnerLocator {

	/**
	 * Current version
	 *
	 * @var  string
	 * @since  NEXT
	 */
	const VERSION = '0.1.0';

	/**
	 * URL of plugin directory
	 *
	 * @var string
	 * @since  NEXT
	 */
	protected $url = '';

	/**
	 * Path of plugin directory
	 *
	 * @var string
	 * @since  NEXT
	 */
	protected $path = '';

	/**
	 * Plugin basename
	 *
	 * @var string
	 * @since  NEXT
	 */
	protected $basename = '';

	/**
	 * Singleton instance of plugin
	 *
	 * @var MapPartnerLocator
	 * @since  NEXT
	 */
	protected static $single_instance = null;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since  NEXT
	 * @return MapPartnerLocator A single instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$single_instance ) {
			self::$single_instance = new self();
		}

		return self::$single_instance;
	}

	/**
	 * Sets up our plugin
	 *
	 * @since  NEXT
	 */
	protected function __construct() {
		$this->basename = plugin_basename( __FILE__ );
		$this->url      = plugin_dir_url( __FILE__ );
		$this->path     = plugin_dir_path( __FILE__ );

		$this->plugin_classes();
	}

	/**
	 * Attach other plugin classes to the base plugin class.
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function plugin_classes() {
		// Attach other plugin classes to the base plugin class.
		// $this->plugin_class = new MPL_Plugin_Class( $this );
	} // END OF PLUGIN CLASSES FUNCTION

	/**
	 * Add hooks and filters
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function hooks() {

		add_action( 'init', 'codex_partner_init' );
		/**
		 * Register a partner post type.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/register_post_type
		 */
		function codex_partner_init() {
			$labels = array(
				'name'               => _x( 'Partners', 'post type general name', 'mappartnerlocator' ),
				'singular_name'      => _x( 'Partner', 'post type singular name', 'mappartnerlocator' ),
				'menu_name'          => _x( 'Partners', 'admin menu', 'mappartnerlocator' ),
				'name_admin_bar'     => _x( 'Partner', 'add new on admin bar', 'mappartnerlocator' ),
				'add_new'            => _x( 'Add New', 'partner', 'mappartnerlocator' ),
				'add_new_item'       => __( 'Add New Partner', 'mappartnerlocator' ),
				'new_item'           => __( 'New Partner', 'mappartnerlocator' ),
				'edit_item'          => __( 'Edit Partner', 'mappartnerlocator' ),
				'view_item'          => __( 'View Partner', 'mappartnerlocator' ),
				'all_items'          => __( 'All Partners', 'mappartnerlocator' ),
				'search_items'       => __( 'Search Partners', 'mappartnerlocator' ),
				'parent_item_colon'  => __( 'Parent Partners:', 'mappartnerlocator' ),
				'not_found'          => __( 'No partners found.', 'mappartnerlocator' ),
				'not_found_in_trash' => __( 'No partners found in Trash.', 'mappartnerlocator' )
			);

			$args = array(
				'labels'             => $labels,
		                'description'        => __( 'Description.', 'mappartnerlocator' ),
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'rewrite'            => array( 'slug' => 'partner' ),
				'capability_type'    => 'post',
				'has_archive'        => true,
				'hierarchical'       => false,
				'menu_position'      => null,
				'supports'           => array( 'title', 'editor', 'author')
			);

			register_post_type( 'partner', $args );
		}
		add_filter( 'post_updated_messages', 'codex_partner_updated_messages' );
		/**
		 * Partner update messages.
		 *
		 * See /wp-admin/edit-form-advanced.php
		 *
		 * @param array $messages Existing post update messages.
		 *
		 * @return array Amended post update messages with new CPT update messages.
		 */
		function codex_partner_updated_messages( $messages ) {
			$post             = get_post();
			$post_type        = get_post_type( $post );
			$post_type_object = get_post_type_object( $post_type );

			$messages['partner'] = array(
				0  => '', // Unused. Messages start at index 1.
				1  => __( 'Partner updated.', 'mappartnerlocator' ),
				2  => __( 'Custom field updated.', 'mappartnerlocator' ),
				3  => __( 'Custom field deleted.', 'mappartnerlocator' ),
				4  => __( 'Partner updated.', 'mappartnerlocator' ),
				/* translators: %s: date and time of the revision */
				5  => isset( $_GET['revision'] ) ? sprintf( __( 'Partner restored to revision from %s', 'mappartnerlocator' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
				6  => __( 'Partner published.', 'mappartnerlocator' ),
				7  => __( 'Partner saved.', 'mappartnerlocator' ),
				8  => __( 'Partner submitted.', 'mappartnerlocator' ),
				9  => sprintf(
					__( 'Partner scheduled for: <strong>%1$s</strong>.', 'mappartnerlocator' ),
					// translators: Publish box date format, see http://php.net/date
					date_i18n( __( 'M j, Y @ G:i', 'mappartnerlocator' ), strtotime( $post->post_date ) )
				),
				10 => __( 'Partner draft updated.', 'mappartnerlocator' )
			);

			if ( $post_type_object->publicly_queryable ) {
				$permalink = get_permalink( $post->ID );

				$view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'View partner', 'mappartnerlocator' ) );
				$messages[ $post_type ][1] .= $view_link;
				$messages[ $post_type ][6] .= $view_link;
				$messages[ $post_type ][9] .= $view_link;

				$preview_permalink = add_query_arg( 'preview', 'true', $permalink );
				$preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), __( 'Preview partner', 'mappartnerlocator' ) );
				$messages[ $post_type ][8]  .= $preview_link;
				$messages[ $post_type ][10] .= $preview_link;
			}

			return $messages;
		}
		//display contextual help for Partners

		function codex_add_help_text( $contextual_help, $screen_id, $screen ) {
		  //$contextual_help .= var_dump( $screen ); // use this to help determine $screen->id
		  if ( 'partner' == $screen->id ) {
		    $contextual_help =
		      '<p>' . __('Things to remember when adding or editing a partner:', 'mappartnerlocator') . '</p>' .
		      '<ul>' .
		      '<li>' . __('Specify the correct genre such as Mystery, or Historic.', 'mappartnerlocator') . '</li>' .
		      '<li>' . __('Specify the correct writer of the partner.  Remember that the Author module refers to you, the author of this partner review.', 'mappartnerlocator') . '</li>' .
		      '</ul>' .
		      '<p>' . __('If you want to schedule the partner review to be published in the future:', 'mappartnerlocator') . '</p>' .
		      '<ul>' .
		      '<li>' . __('Under the Publish module, click on the Edit link next to Publish.', 'mappartnerlocator') . '</li>' .
		      '<li>' . __('Change the date to the date to actual publish this article, then click on Ok.', 'mappartnerlocator') . '</li>' .
		      '</ul>' .
		      '<p><strong>' . __('For more information:', 'mappartnerlocator') . '</strong></p>' .
		      '<p>' . __('<a href="http://codex.wordpress.org/Posts_Edit_SubPanel" target="_blank">Edit Posts Documentation</a>', 'mappartnerlocator') . '</p>' .
		      '<p>' . __('<a href="http://wordpress.org/support/" target="_blank">Support Forums</a>', 'mappartnerlocator') . '</p>' ;
		  } elseif ( 'edit-partner' == $screen->id ) {
		    $contextual_help =
		      '<p>' . __('This is the help screen displaying the table of partners blah blah blah.', 'mappartnerlocator') . '</p>' ;
		  }
		  return $contextual_help;
		}
		add_action( 'contextual_help', 'codex_add_help_text', 10, 3 );

		function codex_custom_help_tab() {

		  $screen = get_current_screen();

		  // Return early if we're not on the partner post type.
		  if ( 'partner' != $screen->post_type )
		    return;

		  // Setup help tab args.
		  $args = array(
		    'id'      => 'you_custom_id', //unique id for the tab
		    'title'   => 'Custom Help', //unique visible title for the tab
		    'content' => '<h3>Help Title</h3><p>Help content</p>',  //actual help text
		  );

		  // Add the help tab.
		  $screen->add_help_tab( $args );

		}

		add_action('admin_head', 'codex_custom_help_tab');

		function default_comments_off( $data ) {
		    if( $data['post_type'] == 'partner' && $data['post_status'] == 'auto-draft' ) {
		        $data['comment_status'] = 0;
		    }

		    return $data;
		}
		add_filter( 'wp_insert_post_data', 'default_comments_off' );

		/**
		 * Adds a meta box to the post editing screen
		 */
		function prfx_custom_meta() {
			add_meta_box( 'prfx_meta', __( 'Location', 'prfx-textdomain' ), 'prfx_meta_callback', 'partner' );
		}
		add_action( 'add_meta_boxes', 'prfx_custom_meta' );
		/**
		 * Outputs the content of the meta box
		 */
		function prfx_meta_callback( $post ) {
			wp_nonce_field( basename( __FILE__ ), 'prfx_nonce' );
			$prfx_stored_meta = get_post_meta( $post->ID );
			?>
			<style>
			#map {
        height: 345px;
        width: 100%;
      }
	      .controls {
	        margin-top: 10px;
	        border: 1px solid transparent;
	        border-radius: 2px 0 0 2px;
	        box-sizing: border-box;
	        -moz-box-sizing: border-box;
	        height: 32px;
	        outline: none;
	        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
	      }

	      #pac-input {
	        background-color: #fff;
	        font-family: Roboto;
	        font-size: 15px;
	        font-weight: 300;
	        margin-left: 12px;
	        padding: 0 11px 0 13px;
	        text-overflow: ellipsis;
	        width: 300px;
	      }

	      #pac-input:focus {
	        border-color: #4d90fe;
	      }

	      .pac-container {
	        font-family: Roboto;
	      }

	      #type-selector {
	        color: #fff;
	        background-color: #4d90fe;
	        padding: 5px 11px 0px 11px;
	      }

	      #type-selector label {
	        font-family: Roboto;
	        font-size: 13px;
	        font-weight: 300;
	      }
	      #target {
	        width: 345px;
	      }
	    </style>

			<input id="pac-input" class="controls" type="text" placeholder="Search Box">
	     <div id="map"></div>
	     <script>
	       // This example adds a search box to a map, using the Google Place Autocomplete
	       // feature. People can enter geographical searches. The search box will return a
	       // pick list containing a mix of places and predicted search terms.

	       // This example requires the Places library. Include the libraries=places
	       // parameter when you first load the API. For example:
	       // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

	       function initAutocomplete() {
				<?php if ( isset ( $prfx_stored_meta['meta-latitude'] ) ) { ?>
					var map = new google.maps.Map(document.getElementById('map'), {
						center: {
						 lat: <?php echo $prfx_stored_meta['meta-latitude'][0]; ?>,
						 lng: <?php echo $prfx_stored_meta['meta-longitude'][0]; ?>
					 	},
						zoom: 24,
						mapTypeId: google.maps.MapTypeId.ROADMAP
					});
					var marker =	new google.maps.Marker({
							map: map,
							title: "Location",
							position: {
								lat: <?php echo $prfx_stored_meta['meta-latitude'][0]; ?>,
								lng: <?php echo $prfx_stored_meta['meta-longitude'][0]; ?>
							}
						});
				<?php } else { ?>
					var map = new google.maps.Map(document.getElementById('map'), {
						center: {lat: -33.8688, lng: 151.2195},
	          zoom: 13,
						mapTypeId: google.maps.MapTypeId.ROADMAP
					});
				<?php } ?>
	         // Create the search box and link it to the UI element.
	         var input = document.getElementById('pac-input');
	         var searchBox = new google.maps.places.SearchBox(input);
	         map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

	         // Bias the SearchBox results towards current map's viewport.
	         map.addListener('bounds_changed', function() {
	           searchBox.setBounds(map.getBounds());
	         });

	         var markers = [];
	         // Listen for the event fired when the user selects a prediction and retrieve
	         // more details for that place.
	         searchBox.addListener('places_changed', function() {
	           var places = searchBox.getPlaces();

	           if (places.length == 0) {
	             return;
	           }

	           // Clear out the old markers.
	           markers.forEach(function(marker) {
	             marker.setMap(null);
	           });
	           markers = [];

	           // For each place, get the icon, name and location.
	           var bounds = new google.maps.LatLngBounds();
	           places.forEach(function(place) {
	             var icon = {
	               url: place.icon,
	               size: new google.maps.Size(71, 71),
	               origin: new google.maps.Point(0, 0),
	               anchor: new google.maps.Point(17, 34),
	               scaledSize: new google.maps.Size(25, 25)
	             };

	             // Create a marker for each place.
	             markers.push(new google.maps.Marker({
	               map: map,
								 icon: icon,
	               title: place.name,
	               position: place.geometry.location
	             }));
							 var latitude = place.geometry.location.lat();
							 var longitude = place.geometry.location.lng();
							 document.getElementById("meta-latitude").value = latitude;
							 document.getElementById("meta-longitude").value = longitude;
							 //place.place_id
	             if (place.geometry.viewport) {
	               // Only geocodes have viewport.
	               bounds.union(place.geometry.viewport);
	             } else {
	               bounds.extend(place.geometry.location);
	             }
	           });
	           map.fitBounds(bounds);
	         });
	       }

	     </script>
	     <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBRzzhuR0g7WNMoDLjHpeaotH2DpBlCfik&libraries=places&callback=initAutocomplete"
	          async defer></script>
			<p>
				<label for="meta-latitude" class="prfx-row-title"><?php _e( 'Latitude', 'prfx-textdomain' )?></label>
				<input type="text" name="meta-latitude" id="meta-latitude" value="<?php if ( isset ( $prfx_stored_meta['meta-latitude'] ) ) echo $prfx_stored_meta['meta-latitude'][0]; ?>" />
			</p>
			<p>
				<label for="meta-longitude" class="prfx-row-title"><?php _e( 'Longitude', 'prfx-textdomain' )?></label>
				<input type="text" name="meta-longitude" id="meta-longitude" value="<?php if ( isset ( $prfx_stored_meta['meta-longitude'] ) ) echo $prfx_stored_meta['meta-longitude'][0]; ?>" />
			</p>
			<?php
		}
		/**
		 * Saves the custom meta input
		 */
		function prfx_meta_save( $post_id ) {

			// Checks save status
			$is_autosave = wp_is_post_autosave( $post_id );
			$is_revision = wp_is_post_revision( $post_id );
			$is_valid_nonce = ( isset( $_POST[ 'prfx_nonce' ] ) && wp_verify_nonce( $_POST[ 'prfx_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

			// Exits script depending on save status
			if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
				return;
			}

			// Checks for input and sanitizes/saves if needed
			if( isset( $_POST[ 'meta-latitude' ] ) ) {
				update_post_meta( $post_id, 'meta-latitude', sanitize_text_field( $_POST[ 'meta-latitude' ] ) );
			}

			// Checks for input and sanitizes/saves if needed
			if( isset( $_POST[ 'meta-longitude' ] ) ) {
				update_post_meta( $post_id, 'meta-longitude', sanitize_text_field( $_POST[ 'meta-longitude' ] ) );
			}

		}
		add_action( 'save_post', 'prfx_meta_save' );
		/**
		 * Adds the meta box stylesheet when appropriate
		 */
		function prfx_admin_styles(){
			global $typenow;
			if( $typenow == 'post' ) {
				wp_enqueue_style( 'prfx_meta_box_styles', plugin_dir_url( __FILE__ ) . 'meta-box-styles.css' );
			}
		}
		add_action( 'admin_print_styles', 'prfx_admin_styles' );
		/**
		 * Loads the color picker javascript
		 */
		function prfx_color_enqueue() {
			global $typenow;
			if( $typenow == 'post' ) {
				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_script( 'meta-box-color-js', plugin_dir_url( __FILE__ ) . 'meta-box-color.js', array( 'wp-color-picker' ) );
			}
		}
		add_action( 'admin_enqueue_scripts', 'prfx_color_enqueue' );

	}

	/**
	 * Activate the plugin
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function _activate() {
		// Make sure any rewrite functionality has been loaded.
		flush_rewrite_rules();
	}

	/**
	 * Deactivate the plugin
	 * Uninstall routines should be in uninstall.php
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function _deactivate() {}

	/**
	 * Init hooks
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function init() {
		if ( $this->check_requirements() ) {
			load_plugin_mappartnerlocator( 'mappartnerlocator', false, dirname( $this->basename ) . '/languages/' );
		}
	}

	/**
	 * Check if the plugin meets requirements and
	 * disable it if they are not present.
	 *
	 * @since  NEXT
	 * @return boolean result of meets_requirements
	 */
	public function check_requirements() {
		if ( ! $this->meets_requirements() ) {

			// Add a dashboard notice.
			add_action( 'all_admin_notices', array( $this, 'requirements_not_met_notice' ) );

			// Deactivate our plugin.
			add_action( 'admin_init', array( $this, 'deactivate_me' ) );

			return false;
		}

		return true;
	}

	/**
	 * Deactivates this plugin, hook this function on admin_init.
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function deactivate_me() {
		deactivate_plugins( $this->basename );
	}

	/**
	 * Check that all plugin requirements are met
	 *
	 * @since  NEXT
	 * @return boolean True if requirements are met.
	 */
	public static function meets_requirements() {
		// Do checks for required classes / functions
		// function_exists('') & class_exists('').
		// We have met all requirements.
		return true;
	}

	/**
	 * Adds a notice to the dashboard if the plugin requirements are not met
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function requirements_not_met_notice() {
		// Output our error.
		echo '<div id="message" class="error">';
		echo '<p>' . sprintf( __( 'Map Partner Locator is missing requirements and has been <a href="%s">deactivated</a>. Please make sure all requirements are available.', 'mappartnerlocator' ), admin_url( 'plugins.php' ) ) . '</p>';
		echo '</div>';
	}

	/**
	 * Magic getter for our object.
	 *
	 * @since  NEXT
	 * @param string $field Field to get.
	 * @throws Exception Throws an exception if the field is invalid.
	 * @return mixed
	 */
	public function __get( $field ) {
		switch ( $field ) {
			case 'version':
				return self::VERSION;
			case 'basename':
			case 'url':
			case 'path':
				return $this->$field;
			default:
				throw new Exception( 'Invalid '. __CLASS__ .' property: ' . $field );
		}
	}

	/**
	 * Include a file from the includes directory
	 *
	 * @since  NEXT
	 * @param  string $filename Name of the file to be included.
	 * @return bool   Result of include call.
	 */
	public static function include_file( $filename ) {
		$file = self::dir( 'includes/class-'. $filename .'.php' );
		if ( file_exists( $file ) ) {
			return include_once( $file );
		}
		return false;
	}

	/**
	 * This plugin's directory
	 *
	 * @since  NEXT
	 * @param  string $path (optional) appended path.
	 * @return string       Directory and path
	 */
	public static function dir( $path = '' ) {
		static $dir;
		$dir = $dir ? $dir : trailingslashit( dirname( __FILE__ ) );
		return $dir . $path;
	}

	/**
	 * This plugin's url
	 *
	 * @since  NEXT
	 * @param  string $path (optional) appended path.
	 * @return string       URL and path
	 */
	public static function url( $path = '' ) {
		static $url;
		$url = $url ? $url : trailingslashit( plugin_dir_url( __FILE__ ) );
		return $url . $path;
	}
}

/**
 * Grab the MapPartnerLocator object and return it.
 * Wrapper for MapPartnerLocator::get_instance()
 *
 * @since  NEXT
 * @return MapPartnerLocator  Singleton instance of plugin class.
 */
function mappartnerlocator() {
	return MapPartnerLocator::get_instance();
}

// Kick it off.
add_action( 'plugins_loaded', array( mappartnerlocator(), 'hooks' ) );

register_activation_hook( __FILE__, array( mappartnerlocator(), '_activate' ) );
register_deactivation_hook( __FILE__, array( mappartnerlocator(), '_deactivate' ) );
