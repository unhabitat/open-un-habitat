<?php
function oipa_get_data_for_url($url) {
  $search_url = OIPA_URL . $url;
  $content = @file_get_contents($search_url);
  if ($content === false) { return false; }
  return $content;
}


/**
 * AJAX calls to OIPA
 */
function refresh_elements() {

	
include( TEMPLATEPATH . '/constants.php' ); 
include( TEMPLATEPATH . '/oipa-functions.php' ); 


  $type = $_GET['call'];

  if ($type === 'projects'){
  	include( TEMPLATEPATH . '/ajax/project-list-ajax.php' );
  } else if ($type === 'projects-on-detail'){
    include( TEMPLATEPATH . '/ajax/project-list-ajax.php');
  } else if ($type === 'countries'){
  	include( TEMPLATEPATH . '/ajax/country-list-ajax.php' );
  } else if ($type === 'regions'){
  	include( TEMPLATEPATH . '/ajax/ajax-list-regions.php' );
  } else if ($type === 'sectors'){
  	include( TEMPLATEPATH . '/ajax/ajax-list-sectors.php' );
  } else if ($type === 'donors'){
  	include( TEMPLATEPATH . '/ajax/donor-list-ajax.php' );
  } else if ($type === 'homepage-total-projects'){
    echo oipa_get_data_for_url('activity-aggregate-any/?format=json&group_by=reporting-org');
  } else if ($type === 'homepage-total-budget'){
    echo oipa_get_data_for_url('activity-aggregate-any/?format=json&group_by=reporting-org&aggregation_key=total-budget');
  } else if ($type === 'homepage-major-programmes'){
    echo oipa_get_data_for_url('sector-activities/?format=json&sectors__in=1,2,3,4,5');
  } else if (in_array($type, array(
    'country-geojson',
    'activities',
    'country-activities',
    'region-activities',
    'global-activities',
    'activity-filter-options',
    'activity-list',
    'sector-activities',
    'donor-activities',
    'donors',
    'regions',
    'countries',
    'sectors'
    ))){
    echo oipa_get_data_for_url($type . '/?' . $_SERVER["QUERY_STRING"]);
  } else {
  	return 'Something went wrong. The call was not foud.';
  }
  
  exit();


}

add_action('wp_ajax_refresh_elements', 'refresh_elements');
add_action('wp_ajax_nopriv_refresh_elements', 'refresh_elements');





add_action( 'after_setup_theme', 'unhabitat_setup' );

function unhabitat_setup()
{
   load_theme_textdomain( 'unhabitat', get_template_directory() . '/languages' );
   add_theme_support( 'post-thumbnails' );
   register_nav_menus(
      array( 
         'main-menu' => __( 'Main Menu', 'unhabitat' ), 
         'footer-menu' => __( 'Footer Menu', 'unhabitat' ) 
           )
   );
}


add_action( 'widgets_init', 'unhabitat_widgets_init' );
function unhabitat_widgets_init() 
{
   register_sidebar( array (
   'name' => __( 'Right Widget Area', 'unhabitat' ),
   'id' => 'right-widget-area',
   'before_widget' => '<div class="gray-col">',
   'after_widget' => "</div>",
   'before_title' => '<h2>',
   'after_title' => '</h2>')  
      );
}



/* Short codes */

function homepage_block_shortcode( $atts, $content = null ) {
	return '<div class="col-md-4"><div class="gray-col gray-col-home">' . do_shortcode($content) . '</div></div>';
}
add_shortcode( 'homepage_block', 'homepage_block_shortcode' );


function faq_block_shortcode( $atts, $content = null ) {
	return '<div class="col-md-4"><div class="gray-col">' . do_shortcode($content) . '</div></div>';
}
add_shortcode( 'faq_block', 'faq_block_shortcode' );


function intro_shortcode( $atts, $content = null ) {
	return '<div class="intro">' . do_shortcode($content) . '</div>';
}
add_shortcode( 'intro', 'intro_shortcode' );


function title_shortcode( $atts, $content = null ) {
	return '<h1 class="title">' . strip_tags($content, '<br>') . '</h1>';
}
add_shortcode( 'title', 'title_shortcode' );



function btn_shortcode( $atts, $content = null ) {
   extract(shortcode_atts(array(
      'url' => '',
      'class' => 'btn-primary',
   ), $atts));
   
	return '<a href="'.$url.'" class="btn '.$class.'">' . esc_attr($content) . '</a>';
}
add_shortcode( 'btn', 'btn_shortcode' );



/* CPT Functions */

if ( ! function_exists('bs_slider_cpt') ) {

// Register Custom Post Type
function bs_slider_cpt() {

	$labels = array(
		'name'                => _x( 'Slides', 'Post Type General Name', 'unhabitat' ),
		'singular_name'       => _x( 'Slide', 'Post Type Singular Name', 'unhabitat' ),
		'menu_name'           => __( 'Slides', 'unhabitat' ),
		'parent_item_colon'   => __( 'Parent Slide:', 'unhabitat' ),
		'all_items'           => __( 'All Slides', 'unhabitat' ),
		'view_item'           => __( 'View Slide', 'unhabitat' ),
		'add_new_item'        => __( 'Add New Slide', 'unhabitat' ),
		'add_new'             => __( 'Add New', 'unhabitat' ),
		'edit_item'           => __( 'Edit Slide', 'unhabitat' ),
		'update_item'         => __( 'Update Slide', 'unhabitat' ),
		'search_items'        => __( 'Search Slides', 'unhabitat' ),
		'not_found'           => __( 'Not found', 'unhabitat' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'unhabitat' ),
	);
	$args = array(
		'label'               => __( 'bs_slider', 'unhabitat' ),
		'description'         => __( 'Bootstrap slider', 'unhabitat' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail', ),
		'taxonomies'          => array( 'category', 'post_tag' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 20,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'rewrite'             => false,
		'capability_type'     => 'page',
	);
	register_post_type( 'bs_slider', $args );

}

// Hook into the 'init' action
add_action( 'init', 'bs_slider_cpt', 0 );

}

function add_rewrite_rules( $wp_rewrite ) 
{
  $new_rules = array(
    'project/([^/]+)/?$' => 'index.php?pagename=project&iati_id='.$wp_rewrite->preg_index(1),
    'country/([^/]+)/?$' => 'index.php?pagename=country&country_id='.$wp_rewrite->preg_index(1),
    'donor/([^/]+)/?$' => 'index.php?pagename=donor&donor_id='.$wp_rewrite->preg_index(1),
    'embed/([^/]+)/?$' => 'index.php?pagename=embed&iati_id='.$wp_rewrite->preg_index(1),
  );
  $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}
add_action('generate_rewrite_rules', 'add_rewrite_rules');




?>
