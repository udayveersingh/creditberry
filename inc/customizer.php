<?php
/**
 * VW Painter Theme Customizer
 *
 * @package VW Painter
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function vw_painter_custom_controls() {

    load_template( trailingslashit( get_template_directory() ) . '/inc/custom-controls.php' );
}
add_action( 'customize_register', 'vw_painter_custom_controls' );

function vw_painter_customize_register( $wp_customize ) {

	load_template( trailingslashit( get_template_directory() ) . 'inc/customize-homepage/class-customize-homepage.php' );

	//add home page setting pannel
	$wp_customize->add_panel( 'vw_painter_panel_id', array(
	    'priority' => 10,
	    'capability' => 'edit_theme_options',
	    'theme_supports' => '',
	    'title' => __( 'VW Settings', 'vw-painter' ),
	    'description' => __( 'Description of what this panel does.', 'vw-painter' ),
	) );

	$wp_customize->add_section( 'vw_painter_left_right', array(
    	'title'      => __( 'General Settings', 'vw-painter' ),
		'priority'   => 30,
		'panel' => 'vw_painter_panel_id'
	) );

	$wp_customize->add_setting('vw_painter_width_option',array(
        'default' => __('Full Width','vw-painter'),
        'sanitize_callback' => 'vw_painter_sanitize_choices'
	));
	$wp_customize->add_control(new VW_Painter_Image_Radio_Control($wp_customize, 'vw_painter_width_option', array(
        'type' => 'select',
        'label' => __('Width Layouts','vw-painter'),
        'description' => __('Here you can change the width layout of Website.','vw-painter'),
        'section' => 'vw_painter_left_right',
        'choices' => array(
            'Full Width' => get_template_directory_uri().'/images/full-width.png',
            'Wide Width' => get_template_directory_uri().'/images/wide-width.png',
            'Boxed' => get_template_directory_uri().'/images/boxed-width.png',
    ))));

	// Add Settings and Controls for Layout
	$wp_customize->add_setting('vw_painter_theme_options',array(
        'default' => __('Right Sidebar','vw-painter'),
        'sanitize_callback' => 'vw_painter_sanitize_choices'	        
	) );
	$wp_customize->add_control('vw_painter_theme_options', array(
        'type' => 'select',
        'label' => __('Post Sidebar Layout','vw-painter'),
        'description' => __('Here you can change the sidebar layout for posts. ','vw-painter'),
        'section' => 'vw_painter_left_right',
        'choices' => array(
            'Left Sidebar' => __('Left Sidebar','vw-painter'),
            'Right Sidebar' => __('Right Sidebar','vw-painter'),
            'One Column' => __('One Column','vw-painter'),
            'Three Columns' => __('Three Columns','vw-painter'),
            'Four Columns' => __('Four Columns','vw-painter'),
            'Grid Layout' => __('Grid Layout','vw-painter')
        ),
	));

	$wp_customize->add_setting('vw_painter_page_layout',array(
        'default' => __('One Column','vw-painter'),
        'sanitize_callback' => 'vw_painter_sanitize_choices'
	));
	$wp_customize->add_control('vw_painter_page_layout',array(
        'type' => 'select',
        'label' => __('Page Sidebar Layout','vw-painter'),
        'description' => __('Here you can change the sidebar layout for pages. ','vw-painter'),
        'section' => 'vw_painter_left_right',
        'choices' => array(
            'Left Sidebar' => __('Left Sidebar','vw-painter'),
            'Right Sidebar' => __('Right Sidebar','vw-painter'),
            'One Column' => __('One Column','vw-painter')
        ),
	) );

	//Topbar
	$wp_customize->add_section( 'vw_painter_topbar', array(
    	'title'      => __( 'Topbar Settings', 'vw-painter' ),
		'priority'   => 30,
		'panel' => 'vw_painter_panel_id'
	) );

	$wp_customize->add_setting('vw_painter_location',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control('vw_painter_location',array(
		'label'	=> __('Add Location','vw-painter'),
		'section'=> 'vw_painter_topbar',
		'type'=> 'text'
	));

	$wp_customize->add_setting('vw_painter_email_address',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control('vw_painter_email_address',array(
		'label'	=> __('Add Email Address','vw-painter'),
		'section'=> 'vw_painter_topbar',
		'type'=> 'text'
	));

	$wp_customize->add_setting('vw_painter_button_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control('vw_painter_button_text',array(
		'label'	=> __('Add Button Text','vw-painter'),
		'section'=> 'vw_painter_topbar',
		'type'=> 'text'
	));

	$wp_customize->add_setting('vw_painter_button_url',array(
		'default'=> '',
		'sanitize_callback'	=> 'esc_url_raw'
	));	
	$wp_customize->add_control('vw_painter_button_url',array(
		'label'	=> __('Add Button Link','vw-painter'),
		'section'=> 'vw_painter_topbar',
		'type'=> 'url'
	));

	$wp_customize->add_setting( 'vw_painter_search_hide_show',
       array(
          'default' => 1,
          'transport' => 'refresh',
          'sanitize_callback' => 'vw_painter_switch_sanitization'
    ));  
    $wp_customize->add_control( new VW_Painter_Toggle_Switch_Custom_Control( $wp_customize, 'vw_painter_search_hide_show',
       array(
          'label' => esc_html__( 'Show / Hide Search','vw-painter' ),
          'section' => 'vw_painter_topbar'
    )));
    
	//Slider
	$wp_customize->add_section( 'vw_painter_slidersettings' , array(
    	'title'      => __( 'Slider Settings', 'vw-painter' ),
		'priority'   => null,
		'panel' => 'vw_painter_panel_id'
	) );

	$wp_customize->add_setting( 'vw_painter_slider_hide_show',
       array(
          'default' => 1,
          'transport' => 'refresh',
          'sanitize_callback' => 'vw_painter_switch_sanitization'
    ));  
    $wp_customize->add_control( new VW_Painter_Toggle_Switch_Custom_Control( $wp_customize, 'vw_painter_slider_hide_show',
       array(
          'label' => esc_html__( 'Show / Hide Slider','vw-painter' ),
          'section' => 'vw_painter_slidersettings'
    )));

	for ( $count = 1; $count <= 4; $count++ ) {

		// Add color scheme setting and control.
		$wp_customize->add_setting( 'vw_painter_slider_page' . $count, array(
			'default'           => '',
			'sanitize_callback' => 'vw_painter_sanitize_dropdown_pages'
		) );
		$wp_customize->add_control( 'vw_painter_slider_page' . $count, array(
			'label'    => __( 'Select Slide Image Page', 'vw-painter' ),
			'description' => __('Slider image size (1500 x 590)','vw-painter'),
			'section'  => 'vw_painter_slidersettings',
			'type'     => 'dropdown-pages'
		) );
	}

	//content layout
	$wp_customize->add_setting('vw_painter_slider_content_option',array(
        'default' => __('Right','vw-painter'),
        'sanitize_callback' => 'vw_painter_sanitize_choices'
	));
	$wp_customize->add_control(new VW_Painter_Image_Radio_Control($wp_customize, 'vw_painter_slider_content_option', array(
        'type' => 'select',
        'label' => __('Slider Content Layouts','vw-painter'),
        'section' => 'vw_painter_slidersettings',
        'choices' => array(
            'Left' => get_template_directory_uri().'/images/slider-content1.png',
            'Center' => get_template_directory_uri().'/images/slider-content2.png',
            'Right' => get_template_directory_uri().'/images/slider-content3.png',
    ))));

    //Slider excerpt
	$wp_customize->add_setting( 'vw_painter_slider_excerpt_number', array(
		'default'              => 30,
		'type'                 => 'theme_mod',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'absint',
		'sanitize_js_callback' => 'absint',
	) );
	$wp_customize->add_control( 'vw_painter_slider_excerpt_number', array(
		'label'       => esc_html__( 'Slider Excerpt length','vw-painter' ),
		'section'     => 'vw_painter_slidersettings',
		'type'        => 'range',
		'settings'    => 'vw_painter_slider_excerpt_number',
		'input_attrs' => array(
			'step'             => 2,
			'min'              => 0,
			'max'              => 50,
		),
	) );

	//Opacity
	$wp_customize->add_setting('vw_painter_slider_opacity_color',array(
      'default'              => 0.5,
      'sanitize_callback' => 'vw_painter_sanitize_choices'
	));

	$wp_customize->add_control( 'vw_painter_slider_opacity_color', array(
	'label'       => esc_html__( 'Slider Image Opacity','vw-painter' ),
	'section'     => 'vw_painter_slidersettings',
	'type'        => 'select',
	'settings'    => 'vw_painter_slider_opacity_color',
	'choices' => array(
      '0' =>  esc_attr('0','vw-painter'),
      '0.1' =>  esc_attr('0.1','vw-painter'),
      '0.2' =>  esc_attr('0.2','vw-painter'),
      '0.3' =>  esc_attr('0.3','vw-painter'),
      '0.4' =>  esc_attr('0.4','vw-painter'),
      '0.5' =>  esc_attr('0.5','vw-painter'),
      '0.6' =>  esc_attr('0.6','vw-painter'),
      '0.7' =>  esc_attr('0.7','vw-painter'),
      '0.8' =>  esc_attr('0.8','vw-painter'),
      '0.9' =>  esc_attr('0.9','vw-painter')
	),
	));

	//Services
	$wp_customize->add_section( 'vw_painter_service_section' , array(
    	'title'      => __( 'Our Services', 'vw-painter' ),
		'priority'   => null,
		'panel' => 'vw_painter_panel_id'
	) );

	$wp_customize->add_setting( 'vw_painter_service_title', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field'
	) );
	$wp_customize->add_control( 'vw_painter_service_title', array(
		'label'    => __( 'Section Title', 'vw-painter' ),		
		'section'  => 'vw_painter_service_section',
		'type'     => 'text'
	) );

	$wp_customize->add_setting( 'vw_painter_service_text', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field'
	) );
	$wp_customize->add_control( 'vw_painter_service_text', array(
		'label'    => __( 'Section Text', 'vw-painter' ),
		'section'  => 'vw_painter_service_section',
		'type'     => 'text'
	) );

	$categories = get_categories();
	$cat_post = array();
	$cat_post[]= 'select';
	$i = 0;	
	foreach($categories as $category){
		if($i==0){
			$default = $category->slug;
			$i++;
		}
		$cat_post[$category->slug] = $category->name;
	}

	$wp_customize->add_setting('vw_painter_services',array(
		'default'	=> 'select',
		'sanitize_callback' => 'vw_painter_sanitize_choices',
	));
	$wp_customize->add_control('vw_painter_services',array(
		'type'    => 'select',
		'choices' => $cat_post,		
		'label' => __('Select Category to display services','vw-painter'),
		'description' => __('Services Icon size (75 x 65)','vw-painter'),
		'section' => 'vw_painter_service_section',
	));

	//Services excerpt
	$wp_customize->add_setting( 'vw_painter_services_excerpt_number', array(
		'default'              => 30,
		'type'                 => 'theme_mod',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'absint',
		'sanitize_js_callback' => 'absint',
	) );
	$wp_customize->add_control( 'vw_painter_services_excerpt_number', array(
		'label'       => esc_html__( 'Services Excerpt length','vw-painter' ),
		'section'     => 'vw_painter_service_section',
		'type'        => 'range',
		'settings'    => 'vw_painter_services_excerpt_number',
		'input_attrs' => array(
			'step'             => 2,
			'min'              => 0,
			'max'              => 50,
		),
	) );	

	//Blog Post
	$wp_customize->add_section('vw_painter_blog_post',array(
		'title'	=> __('Blog Post Settings','vw-painter'),
		'panel' => 'vw_painter_panel_id',
	));	

	$wp_customize->add_setting( 'vw_painter_toggle_postdate',array(
        'default' => 1,
        'transport' => 'refresh',
        'sanitize_callback' => 'vw_painter_switch_sanitization'
    ) );
    $wp_customize->add_control( new VW_Painter_Toggle_Switch_Custom_Control( $wp_customize, 'vw_painter_toggle_postdate',array(
        'label' => esc_html__( 'Post Date','vw-painter' ),
        'section' => 'vw_painter_blog_post'
    )));

    $wp_customize->add_setting( 'vw_painter_toggle_author',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'vw_painter_switch_sanitization'
    ) );
    $wp_customize->add_control( new VW_Painter_Toggle_Switch_Custom_Control( $wp_customize, 'vw_painter_toggle_author',array(
		'label' => esc_html__( 'Author','vw-painter' ),
		'section' => 'vw_painter_blog_post'
    )));

    $wp_customize->add_setting( 'vw_painter_toggle_comments',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'vw_painter_switch_sanitization'
    ) );
    $wp_customize->add_control( new VW_Painter_Toggle_Switch_Custom_Control( $wp_customize, 'vw_painter_toggle_comments',array(
		'label' => esc_html__( 'Comments','vw-painter' ),
		'section' => 'vw_painter_blog_post'
    )));

    $wp_customize->add_setting( 'vw_painter_excerpt_number', array(
		'default'              => 30,
		'type'                 => 'theme_mod',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'absint',
		'sanitize_js_callback' => 'absint',
	) );
	$wp_customize->add_control( 'vw_painter_excerpt_number', array(
		'label'       => esc_html__( 'Excerpt length','vw-painter' ),
		'section'     => 'vw_painter_blog_post',
		'type'        => 'range',
		'settings'    => 'vw_painter_excerpt_number',
		'input_attrs' => array(
			'step'             => 2,
			'min'              => 0,
			'max'              => 50,
		),
	) );

	//Content Craetion
	$wp_customize->add_section( 'vw_painter_content_section' , array(
    	'title' => __( 'Customize Home Page', 'vw-painter' ),
		'priority' => null,
		'panel' => 'vw_painter_panel_id'
	) );

	$wp_customize->add_setting('vw_painter_content_creation_main_control', array(
		'sanitize_callback' => 'esc_html',
	) );

	$homepage= get_option( 'page_on_front' );

	$wp_customize->add_control(	new VW_Painter_Content_Creation( $wp_customize, 'vw_painter_content_creation_main_control', array(
		'options' => array(
			esc_html__( 'First select static page in homepage setting for front page.Below given edit button is to customize Home Page. Just click on the edit option, add whatever elements you want to include in the homepage, save the changes and you are good to go.','vw-painter' ),
		),
		'section' => 'vw_painter_content_section',
		'button_url'  => admin_url( 'post.php?post='.$homepage.'&action=edit'),
		'button_text' => esc_html__( 'Edit', 'vw-painter' ),
	) ) );

	//Footer Text
	$wp_customize->add_section('vw_painter_footer',array(
		'title'	=> __('Footer','vw-painter'),
		'description'=> __('This section will appear in the footer','vw-painter'),
		'panel' => 'vw_painter_panel_id',
	));	
	
	$wp_customize->add_setting('vw_painter_footer_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control('vw_painter_footer_text',array(
		'label'	=> __('Copyright Text','vw-painter'),
		'section'=> 'vw_painter_footer',
		'setting'=> 'vw_painter_footer_text',
		'type'=> 'text'
	));	

	$wp_customize->add_setting( 'vw_painter_hide_show_scroll',array(
    	'default' => 1,
      	'transport' => 'refresh',
      	'sanitize_callback' => 'vw_painter_switch_sanitization'
    ));  
    $wp_customize->add_control( new VW_Painter_Toggle_Switch_Custom_Control( $wp_customize, 'vw_painter_hide_show_scroll',array(
      	'label' => esc_html__( 'Show / Hide Scroll To Top','vw-painter' ),
      	'section' => 'vw_painter_footer'
    )));

	$wp_customize->add_setting('vw_painter_scroll_top_alignment',array(
        'default' => __('Right','vw-painter'),
        'sanitize_callback' => 'vw_painter_sanitize_choices'
	));
	$wp_customize->add_control(new VW_Painter_Image_Radio_Control($wp_customize, 'vw_painter_scroll_top_alignment', array(
        'type' => 'select',
        'label' => __('Scroll To Top','vw-painter'),
        'section' => 'vw_painter_footer',
        'settings' => 'vw_painter_scroll_top_alignment',
        'choices' => array(
            'Left' => get_template_directory_uri().'/images/layout1.png',
            'Center' => get_template_directory_uri().'/images/layout2.png',
            'Right' => get_template_directory_uri().'/images/layout3.png'
    ))));
}

add_action( 'customize_register', 'vw_painter_customize_register' );

load_template( trailingslashit( get_template_directory() ) . '/inc/logo/logo-resizer.php' );

/**
 * Singleton class for handling the theme's customizer integration.
 *
 * @since  1.0.0
 * @access public
 */
final class VW_Painter_Customize {

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self;
			$instance->setup_actions();
		}

		return $instance;
	}

	/**
	 * Constructor method.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Sets up initial actions.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup_actions() {

		// Register panels, sections, settings, controls, and partials.
		add_action( 'customize_register', array( $this, 'sections' ) );

		// Register scripts and styles for the controls.
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_control_scripts' ), 0 );
	}

	/**
	 * Sets up the customizer sections.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $manager
	 * @return void
	 */
	public function sections( $manager ) {

		// Load custom sections.
		load_template( trailingslashit( get_template_directory() ) . '/inc/section-pro.php' );

		// Register custom section types.
		$manager->register_section_type( 'VW_Painter_Customize_Section_Pro' );

		// Register sections.
		$manager->add_section(new VW_Painter_Customize_Section_Pro($manager,'example_1',array(
			'priority'   => 1,
			'title'    => esc_html__( 'Painter Pro Theme', 'vw-painter' ),
			'pro_text' => esc_html__( 'UPGRADE PRO', 'vw-painter' ),
			'pro_url'  => esc_url('https://www.vwthemes.com/themes/painter-wordpress-theme/'),
		)));

		// Register sections.
		$manager->add_section(new VW_Painter_Customize_Section_Pro($manager,'example_2',array(
			'priority'   => 1,
			'title'    => esc_html__( 'DOCUMENTATION', 'vw-painter' ),
			'pro_text' => esc_html__( 'DOCS', 'vw-painter' ),
			'pro_url'  => admin_url( 'themes.php?page=vw_painter_guide' )
		)));
	}

	/**
	 * Loads theme customizer CSS.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue_control_scripts() {

		wp_enqueue_script( 'vw-painter-customize-controls', trailingslashit( get_template_directory_uri() ) . '/js/customize-controls.js', array( 'customize-controls' ) );

		wp_enqueue_style( 'vw-painter-customize-controls', trailingslashit( get_template_directory_uri() ) . '/css/customize-controls.css' );
	}
}

// Doing this customizer thang!
VW_Painter_Customize::get_instance();