<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
*
* Framework Class
*
* @since 1.0.0
* @version 1.0.0
*
*/
class CSFramework extends CSFramework_Abstract {
	/**
	 * type
	 *
	 * @var string
	 */
	protected $type = 'settings';

	/**
	 * framework_defaults
	 *
	 * @var array
	 */
	protected $framework_defaults = array(
		'menu_type'         	=> '',
		'menu_parent'       	=> '',
		'menu_title'        	=> 'CSF Settings',
		'menu_slug'         	=> 'csf',
		'menu_capability'   	=> 'manage_options',
		'menu_icon'         	=> null,
		'menu_position'     	=> null,
		'show_submenus'     	=> false,
		'framework_title' 		=> 'CSF Settings Framework',
    	'framework_subtitle' 	=> 'by CastorStudio',
		'ajax_save'         	=> false,
		'buttons'           	=> array(),
		'option_name'       	=> false,
		'override_location' 	=> '',
		'extra_css'         	=> array(),
		'extra_js'          	=> array(),
		'is_single_page'    	=> false,
		'is_sticky_header'  	=> false,
		'style'             	=> 'modern',
		'help_tabs'         	=> array(),
		'show_all_options_link'	=> false,
	);

	/**
	 * fields_md5
	 *
	 * @var null
	 */
	protected $fields_md5 = null;

	/**
	 * page_hook
	 *
	 * @var null
	 */
	protected $page_hook = null;

	/**
	 * menus
	 *
	 * @var array
	 */
	protected $menus = array();

	/**
	 * active_menu
	 *
	 * @var array
	 */
	private $active_menu = array();

	/**
	 * csframework_Settings constructor.
	 *
	 * @param array  $settings
	 * @param array  $fields
	 * @param string $plugin_id
	 */
	public function __construct( $settings = array(), $fields = array(), $plugin_id = '' ) {
		// if ( ! empty( $fields ) && ! empty( $settings ) && $this->is_not_ajax() ) {
		if ( ! empty( $settings ) && $this->is_not_ajax() ) {
			// $this->raw_options                       = $fields;
			$this->framework_defaults['option_name'] = $this->unique;
			$settings                                = wp_parse_args( $settings, $this->framework_defaults );
			$settings['buttons']                     = wp_parse_args( $settings['buttons'], array(
				'save'    => __( 'Save', 'csf' ),
				'restore' => __( 'Restore', 'csf' ),
				'reset'   => __( 'Reset All Options', 'csf' ),
			) );

			$this->plugin_id         = empty( $plugin_id ) ? $settings['option_name'] : $plugin_id;
			$this->settings          = $this->_filter( 'settings', $settings );
			// $this->options           = $this->_filter( 'fields', $fields );
			$this->unique            = ( ! empty( $this->settings['option_name'] ) ) ? $this->settings['option_name'] : $this->unique;
			$this->override_location = ( isset( $settings['override_location'] ) ) ? $settings['override_location'] : false;
			// $this->addAction( 'admin_init', 'register_settings' );
			$this->addAction( 'admin_menu', 'admin_menu' );
			// csf_registry()->add( $this );
		}
	}
	public function set_options($fields = array()){
		$this->raw_options  = $fields; // apply_filters( 'csf_framework_options', $fields , $this->unique);
		$this->options		= $this->_filter( 'options', $fields );
		$this->sections   	= $this->get_sections();
		$this->addAction( 'admin_init', 'register_settings' );
		csf_registry()->add( $this );
		// $this->addAction( 'admin_init', 'settings_api' );
		
		// echo "<h2>Seteando opciones para: {$this->unique}</h2>";
		// echo '<pre>';
		// print_r($this->sections);
		// echo '</pre>';

	}








	/**
	 * Register Settings To WP
	 */
	public function register_settings() {
		$cache = $this->get_cache();
		register_setting( $this->unique, $this->unique, array(
			'sanitize_callback' => array( &$this, 'validate_save' ),
		) );

		if ( ! isset( $cache['md5'] ) || ( isset( $cache['md5'] ) && $cache['md5'] !== $this->fields_md5() ) ) {
			$this->set_defaults();
		}
	}

	/**
	 * Retrives Cache From DB and returns it
	 *
	 * @return array
	 */
	public function get_cache() {
		if ( empty( $this->cache ) ) {
			$cache       = get_option( $this->unique . '-transient', array() );
			$this->cache = ( is_array( $cache ) ) ? $cache : array();
		}
		return $this->cache;
	}

	/**
	 * Encodes raw_options array and converts into MD5 to get an unique ID for settings fields
	 *
	 * @return null|string
	 */
	protected function fields_md5() {
		if ( empty( $this->fields_md5 ) ) {
			$this->fields_md5 = md5( json_encode( $this->raw_options ) );
		}
		return $this->fields_md5;
	}

	/**
	 * Sets Default Value To DB.
	 */
	public function set_defaults() {
		$defaults = array();
		$this->get_db_options();

		foreach ( $this->get_sections() as $section ) {
			foreach ( $section['fields'] as $field_key => $field ) {
				if ( isset( $field['default'] ) && ! isset( $this->db_options[ $field['id'] ] ) ) {
					$defaults[ $field['id'] ]         = $field['default'];
					$this->db_options[ $field['id'] ] = $field['default'];
				}
			}
		}

		if ( ! empty( $defaults ) ) {
			update_option( $this->unique, $this->db_options );
		}
		$this->cache['md5'] = $this->fields_md5();
		$this->set_cache( $this->cache );
	}

	/**
	 * Retrives Stored Options From DB
	 *
	 * @return array|mixed
	 */
	public function get_db_options() {
		if ( empty( $this->db_options ) ) {
			$this->db_options = get_option( $this->unique, true );
			$this->db_options = ( empty( $this->db_options ) || true === $this->db_options ) ? array() : $this->db_options;
		}
		return $this->db_options;
	}

	/**
	 * @return array
	 */
	protected function get_sections() {
		$sections = array();
		foreach ( $this->options as $key => $page ) {
			$page_id = $page['name'];
			if ( isset( $page['sections'] ) ) {
				foreach ( $page['sections'] as $_key => $section ) {
					$section_id = $section['name'];
					if ( isset( $section['fields'] ) ) {
						$section['page_id']                       = $page_id;
						$sections[ $page_id . '/' . $section_id ] = $section;
					}
				}
			} else {
				if ( isset( $page['callback_hook'] ) ) {
					$page['fields'] = array();
				}

				if ( isset( $page['fields'] ) ) {
					$page['page_id']      = false;
					$sections[ $page_id ] = $page;
				}
			}
		}

		return $sections;
	}

	/**
	 * @param array $data
	 */
	public function set_cache( $data = array() ) {
		update_option( $this->unique . '-transient', $data );
		$this->cache = $data;
	}

	/**
	 * @param $request
	 *
	 * @return array
	 */
	public function validate_save( $request ) {
		$this->options = $this->map_error_id( $this->options );
		$this->find_active_menu();
		$add_errors = array();
        $section_id = $this->active( false );
        $parent_section_id = $this->active( true );

		if ( isset( $request['_nonce'] ) ) {
			unset( $request ['_nonce'] );
		}

		if( isset ($request ['import']) && ! empty ($request ['import']) ) {
            $decode_string = csf_decode_string($request ['import']);
            if( is_array($decode_string) ) {
                return $decode_string;
            }
            $add_errors[] = csf_add_errors(esc_html__('Success. Imported backup options.', 'csf-framework'), 'updated');
        }
        if( isset ($request ['resetall']) ) {
            $add_errors[] = csf_add_errors(esc_html__('Default options restored.', 'csf-framework'), 'updated');
            return;
		}
		// reset only section
		if( isset($request['reset']) && ! empty($section_id) ) {
			// echo "Seccion: $section_id -- $parent_section_id <br>";
			// echo $this->_sec_id($section_id, $parent_section_id);
			// echo '<pre>';
			// print_r($this->sections);
			// echo '</pre>';
			foreach ( $this->sections as $value ) {
				if( $value['name'] == $section_id ) {
					foreach ( $value['fields'] as $field ) {
						if( isset( $field['id'] ) ) {
							if( isset( $field['default'] ) ) {
								$request[$field['id']] = $field['default'];
					  		} else {
								unset( $request[$field['id']] );
					  		}
						}
				  	}
				}
			  }
            $add_errors[] = csf_add_errors(esc_html__('Default options restored for only this section.', 'wpsf-framework'), 'updated');
		}

		$save_handler = new CSFramework_DB_Save_Handler();
		$request      = $save_handler->handle_settings_page( array(
			'is_single_page'     => $this->is( 'single_page' ),
			'current_section_id' => $section_id,
			'current_parent_id'  => $parent_section_id,
			'db_key'             => $this->unique,
			'posted_values'      => $request,
		), $this->get_sections() );

		$add_errors = $save_handler->get_errors();
		$request = apply_filters("csf_validate_save", $request, $this);
		do_action("csf_validate_save_after", $request, $this->unique);
		
		unset( $this->cache['parent_section_id'] );
		$this->cache['errors']     = $add_errors;
		$this->cache['section_id'] = $section_id;
		$this->cache['parent_id']  = $parent_section_id;
		$this->set_cache( $this->cache );
		return $request;
	}

	/**
     * @param string $section_id
     * @param bool   $parent_id
     * @return string
     */
    protected function _sec_id($section_id = '', $parent_id = FALSE) {
        return ( $parent_id === FALSE ) ? $section_id : $parent_id . '/' . $section_id;
	}
	


	/**
	 * Finds Active Menu for the given options
	 */
	private function find_active_menu() {
		$cache  = $this->get_cache();
		$_cache = array(
			'section_id' => ( ! empty( $cache['section_id'] ) ) ? $cache['section_id'] : false,
			'parent_id'  => ( ! empty( $cache['parent_id'] ) ) ? $cache['parent_id'] : false,
		);
		$_url   = array(
			'section_id' => csf_get_var( 'csf-section-id', false ),
			'parent_id'  => csf_get_var( 'csf-parent-id', false ),
		);

		$_cache_v = $this->validate_section_ids( $_cache );
		$_url_v   = $this->validate_section_ids( $_url );

		if ( false !== $_cache_v ) {
			$default = $this->validate_sections( $_cache_v['parent_id'], $_cache_v['section_id'] );

			$this->cache['section_id'] = false;
			$this->cache['parent_id']  = false;
			$this->set_cache( $this->cache );
		} elseif ( false !== $_url_v ) {
			$default = $this->validate_sections( $_url_v['parent_id'], $_url_v['section_id'] );
		} else {
			$default = $this->validate_sections( false, false );
		}

		if ( ( is_null( $default['section_id'] ) || false === $default['section_id'] ) && $default['parent_id'] ) {
			$default['section_id'] = $default['parent_id'];
		}
		// echo "<br>Setando active menu como:";
		// print_r($default);
		$this->active_menu = $default;
	}

	/**
	 * Validate Given Section IDS
	 *
	 * @param array $ids
	 *
	 * @return array|bool
	 */
	public function validate_section_ids( $ids = array() ) {
		if ( empty( array_filter( $ids ) ) ) {
			return false;
		} elseif ( empty( $ids['section_id'] ) && ! empty( $ids['parent_id'] ) ) {
			return array(
				'section_id' => false,
				'parent_id'  => $ids['parent_id'],
			);
		} elseif ( ! empty( $ids['section_id'] ) && empty( $ids['parent_id'] ) ) {
			return array(
				'section_id' => false,
				'parent_id'  => $ids['section_id'],
			);
		} else {
			return array(
				'section_id' => $ids['section_id'],
				'parent_id'  => $ids['parent_id'],
			);
		}
	}

	/**
	 * @param string $parent_id
	 * @param string $section_id
	 *
	 * @return array
	 */
	public function validate_sections( $parent_id = '', $section_id = '' ) {
		$parent_id  = $this->is_page_section_exists( $parent_id, $section_id );
		$section_id = $this->is_page_section_exists( $parent_id, $section_id, true );
		return array(
			'section_id' => $section_id,
			'parent_id'  => $parent_id,
		);
	}

	/**
	 * @param string $page_id
	 * @param string $section_id
	 * @param bool   $is_section
	 *
	 * @return bool|null|string
	 */
	public function is_page_section_exists( $page_id = '', $section_id = '', $is_section = false ) {
		foreach ( $this->options as $option ) {
			if ( $option['name'] === $page_id && false === $is_section ) {
				return $page_id;
			} elseif ( $option['name'] === $page_id && isset( $option['sections'] ) ) {
				foreach ( $option['sections'] as $section ) {
					if ( $section['name'] === $section_id ) {
						return $section_id;
					}
				}
			}
		}

		$page_id = ( true === $is_section ) ? $page_id : null;
		return $this->get_page_section_id( $is_section, $page_id );
	}

	/**
	 * @param bool $is_section
	 * @param null $page
	 *
	 * @return bool|null
	 */
	private function get_page_section_id( $is_section = true, $page = null ) {
		if ( null !== $page ) {
			foreach ( $this->options as $option ) {
				if ( $option['name'] === $page && false === $is_section ) {
					return $option['name'];
				} elseif ( $option['name'] === $page && true === $is_section && isset( $option['sections'] ) ) {
					$cs = current( $option['sections'] );
					return $cs['name'];
				}
			}
		} else {
			$cs = current( $this->options );
			if ( true === $is_section && isset( $cs['sections'] ) ) {
				$cs = current( $cs['sections'] );
				return $cs['name'];
			}

			return isset( $cs['name'] ) ? $cs['name'] : false;
		}
		return false;
	}

	/**
	 * @param string $type
	 * @param bool   $status
	 *
	 * @return bool|mixed|string
	 */
	public function is( $type = '', $status = false ) {
		switch ( $type ) {
			case 'single_page':
			case 'sp':
				return ( $this->_option( 'is_single_page' ) === true ) ? true : false;
				break;

			case 'sticky_header':
			case 'sticky_head':
				return ( $this->_option( 'is_sticky_header' ) === true ) ? true : false;
				break;
			case 'ajax_save':
				return $this->_option( 'ajax_save' );
				break;
			case 'has_nav':
				return ( count( $this->options ) <= 1 ) ? false : true;
				break;
			case 'page_active':
				return ( true === $status ) ? 'style="display:block;"' : '';
			default:
				return false;
				break;
		}
	}

	/**
	 * Returns Current active Menu
	 *
	 * @param bool $is_parent
	 *
	 * @return bool|mixed
	 */
	public function active( $is_parent = true ) {
		if ( true === $is_parent ) {
			return ( isset( $this->active_menu['parent_id'] ) ) ? $this->active_menu['parent_id'] : false;
		}
		return ( isset( $this->active_menu['section_id'] ) ) ? $this->active_menu['section_id'] : false;
	}

	/**
	 * Adds Admin Menu
	 */
	public function admin_menu() {
		$pm        = $this->settings['menu_parent'];
		$type      = $this->settings['menu_type'];
		$i         = $this->settings['menu_icon'];
		$p         = $this->settings['menu_position'];
		// $p 		   = 1113.13;
		$_t        = $this->settings['menu_title'];
		$ac        = $this->settings['menu_capability'];
		$slug      = $this->settings['menu_slug'];
		$menu_type = 'parent';

		switch ( $type ) {
			case 'submenu':
				$menu_type       = 'submenu';
				$this->page_hook = add_submenu_page( $pm, $_t, $_t, $ac, $slug, array( &$this, 'render_page' ) );
				break;
			case 'management':
			case 'dashboard':
			case 'options':
			case 'plugins':
			case 'theme':
				$menu_type = 'submenu';
				$f         = 'add_' . $type . '_page';
				if ( function_exists( $f ) ) {
					$this->page_hook = $f( $_t, $_t, $ac, $slug, array( &$this, 'render_page' ), $i, $p );
				}
				break;
			default:
				$this->page_hook = add_menu_page( $_t, $_t, $ac, $slug, array( &$this, 'render_page' ), $i, $p );
				break;
		}

		$this->_action( 'admin_menu', $this->page_hook, $menu_type, $this );
		$this->addAction( 'load-' . $this->page_hook, 'init_page' );
	}

	/**
	 * Renders HTML Source
	 */
	public function render_page() {
		csf_template( $this->override_location, 'settings.php', array( 'class' => $this ) );
	}

	/**
	 * Runs @ load-{$page_hook} action
	 */
	public function init_page() {
		$this->addAction( 'admin_enqueue_scripts', 'load_assets' );
		$this->options = $this->map_error_id( $this->options );
		$this->get_db_options();
		$this->find_active_menu();
		$this->menus = $this->filter( 'menus', $this->extract_menus(), $this );
		$errors      = ( isset( $this->cache['errors'] ) ) ? $this->cache['errors'] : array();
		csf_add_errors( $errors );
	}

	/**
	 * Extract Menus From the options array
	 *
	 * @param array       $ops
	 * @param string|bool $parent
	 *
	 * @return array
	 */
	public function extract_menus( $ops = array(), $parent = false ) {
		$output = array();
		$array  = ( empty( $ops ) ) ? $this->options : $ops;

		foreach ( $array as $option ) {
			$name = isset( $option['name'] ) ? $option['name'] : '';
			if ( isset( $option['sections'] ) ) {

				$is_active       = ( $this->active( true ) === $option['name'] ) ? true : false;
				$output[ $name ] = array(
					'name'            => $name,
					'title'           => ( isset( $option['title'] ) ) ? $option['title'] : '',
					'icon'            => ( isset( $option['icon'] ) ) ? $option['icon'] : '',
					'is_separator'    => false,
					'is_active'       => $is_active,
					'is_internal_url' => ( isset( $option['href'] ) ) ? false : true,
					'href'            => ( isset( $option['href'] ) ) ? $option['href'] : $this->get_tab_url( $name, null ),
					'submenus'        => $this->_filter( 'submenu', $this->extract_menus( $option['sections'], $name ), $name ),
					'query_args'      => ( isset( $option['query_args'] ) ) ? $option['query_args'] : '',
				);

			} else {
				$is_active = ( $this->active( false ) === $option['name'] ) ? true : false;

				$output[ $name ] = array(
					'name'            => $name,
					'title'           => ( isset( $option['title'] ) ) ? $option['title'] : '',
					'icon'            => ( isset( $option['icon'] ) ) ? $option['icon'] : '',
					'is_internal_url' => ( isset( $option['href'] ) ) ? false : true,
					'href'            => ( isset( $option['href'] ) ) ? $option['href'] : $this->get_tab_url( $name, $parent ),
					'submenus'        => array(),
					'is_active'       => $is_active,
					'is_separator'    => ( isset( $option['fields'] ) || isset( $option['callback_hook'] ) || isset( $option['href'] ) || isset( $option['query_args'] ) ) ? false : true,
					'query_args'      => ( isset( $option['query_args'] ) ) ? $option['query_args'] : '',
				);
			}
		}
		return $output;
	}

	/**
	 * @param string $section
	 * @param string $parent
	 *
	 * @return string
	 */
	public function get_tab_url( $section = '', $parent = '' ) {
		if ( $this->is( 'single_page' ) !== true ) {
			$data = array(
				'csf-section-id' => $section,
				'csf-parent-id'  => $parent,
			);
			$url  = remove_query_arg( array_keys( $data ) );
			return add_query_arg( array_filter( $data ), $url );
		}
		return '#';
	}


	/**
	 * Register & Loads Required csf Assets
	 */
	public function load_assets() {
		csf_assets()->render_framework_style_scripts();

		if ( isset( $this->settings['extra_css'] ) && is_array( $this->settings['extra_css'] ) ) {
			foreach ( $this->settings['extra_css'] as $id ) {
				wp_enqueue_style( $id );
			}
		}

		if ( isset( $this->settings['extra_js'] ) && is_array( $this->settings['extra_js'] ) ) {
			foreach ( $this->settings['extra_js'] as $id ) {
				wp_enqueue_script( $id );
			}
		}
	}

	/**
	 * Returns Active Theme Name
	 *
	 * @return bool|mixed|string
	 */
	public function theme() {
		return ( ! empty( $this->_option( 'style' ) ) ) ? $this->_option( 'style' ) : 'modern';
	}

	/**
	 * Returns Settings Button
	 *
	 * @return string
	 */
	public function get_settings_buttons() {
		$this->catch_output( 'start' );
		if ( false !== $this->settings['buttons']['save'] ) {
			$text = ( true === $this->settings['buttons']['save'] ) ? 'Save' : $this->settings['buttons']['save'];
			submit_button( esc_html( $text ), 'primary csf-save', 'save', false, array( 'data-save' => esc_html__( 'Saving...', 'csf' ) ) );
		}

		if ( false !== $this->settings['buttons']['restore'] ) {
			$text = ( true === $this->settings['buttons']['restore'] ) ? 'Save' : $this->settings['buttons']['restore'];
			submit_button( esc_html( $text ), 'secondary csf-restore csf-reset-confirm', $this->unique . '[reset]', false );
		}

		if ( false !== $this->settings['buttons']['reset'] ) {
			$text = ( true === $this->settings['buttons']['reset'] ) ? "Reset All Options" : $this->settings['buttons']['reset'];
			submit_button( $text, 'secondary csf-restore csf-warning-primary csf-reset-confirm', $this->unique . '[resetall]', false );
		}

		return $this->catch_output( false );
	}

	/**
	 * Returns Menus List
	 *
	 * @return array
	 */
	public function navs() {
		return $this->menus;
	}

	/**
	 * Renders Icon HTML
	 *
	 * @param $data
	 *
	 * @return string
	 */
	public function icon( $data ) {
		return ( isset( $data['icon'] ) && ! empty( $data['icon'] ) ) ? '<i class="csf-icon ' . $data['icon'] . '"></i>' : '';
	}

	/**
	 * @param $data
	 *
	 * @return string
	 */
	public function get_title( $data ) {
		return ( isset( $data['title'] ) && ! empty( $this->is( 'has_nav' ) ) ) ? '<div class="csf-section-title"><h3>' . $data['title'] . '</h3></div>' : '';
	}

	/**
	 * @param $data
	 *
	 * @return bool|string
	 */
	public function render_fields( $data ) {
		if ( isset( $data['callback_hook'] ) ) {
			$this->catch_output();
			do_action( $data['callback_hook'], $this );
			return $this->catch_output( 'end' );
		} elseif ( isset( $data['fields'] ) ) {
			$r = '';
			foreach ( $data['fields'] as $field ) {
				$r .= $this->field_callback( $field );
			}
			return $r;
		}
		return false;
	}

	/**
	 * @param $field
	 *
	 * @return string
	 */
	public function field_callback( $field ) {
		$value = $this->get_field_values( $field, $this->get_db_options() );
		return csf_add_element( $field, $value, $this->unique );
	}
}











