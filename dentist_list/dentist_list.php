<?php
/*
Plugin Name: Dentist List
Plugin URI: http://ke1pl.com
Description: Plugin to List of dentists
Version: 1.0
Author: Ke1pl
Author URI: http://ke1pl.com
*/

class dentist_list {

	function dentist_list () {
	}
}

$dentist_list = new dentist_list();

	// Our custom post type function
function create_posttype() {

	register_post_type( 'dentists',
	// CPT Options
		array(
			'labels' => array(
				'name' => __( 'Dentists' ),
				'singular_name' => __( 'Dentist' )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'dentists'),
		)
	);
}
// Hooking up our function to theme setup
add_action( 'init', 'create_posttype' );

/*
* Creating a function to create our CPT
*/

function custom_post_type() {

// Set UI labels for Custom Post Type
	$labels = array(
		'name'                => _x( 'Dentists', 'Post Type General Name', 'twentythirteen' ),
		'singular_name'       => _x( 'Dentist', 'Post Type Singular Name', 'twentythirteen' ),
		'menu_name'           => __( 'Dentists', 'twentythirteen' ),
		'parent_item_colon'   => __( 'Parent Dentist', 'twentythirteen' ),
		'all_items'           => __( 'All Dentists', 'twentythirteen' ),
		'view_item'           => __( 'View Dentist', 'twentythirteen' ),
		'add_new_item'        => __( 'Add New Dentist', 'twentythirteen' ),
		'add_new'             => __( 'Add New', 'twentythirteen' ),
		'edit_item'           => __( 'Edit Dentist', 'twentythirteen' ),
		'update_item'         => __( 'Update Dentist', 'twentythirteen' ),
		'search_items'        => __( 'Search Dentist', 'twentythirteen' ),
		'not_found'           => __( 'Not Found', 'twentythirteen' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'twentythirteen' ),
	);
	
// Set other options for Custom Post Type
	
	$args = array(
		'label'               => __( 'dentists', 'twentythirteen' ),
		'description'         => __( 'Dentist info ', 'twentythirteen' ),
		'labels'              => $labels,
		// Features this CPT supports in Post Editor
		//'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
		'supports'            => array( 'title', 'editor',),
		// You can associate this CPT with a taxonomy or custom taxonomy. 
		'taxonomies'          => array( 'genres' ),
		/* A hierarchical CPT is like Pages and can have
		* Parent and child items. A non-hierarchical CPT
		* is like Posts.
		*/	
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
	
	// Registering your Custom Post Type
	register_post_type( 'dentists', $args );

}

/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/

add_action( 'init', 'custom_post_type', 0 );


add_action('add_meta_boxes', 'metatest_init'); 
add_action('save_post', 'metatest_save'); 
register_uninstall_hook(__FILE__, 'mds_uninstall');

$default_metadata = array(
'name' => '',
'contacts' => '',
'link' => '',
'type' => '',
'email' => ''
);

function metatest_init() { 
add_meta_box('metatest', 'Additional info', 'metatest_showup', 'dentists', 'side', 'high'); 
} 

function metatest_showup($post, $box) { 

global $default_metadata;
if (!$md = get_post_meta($post->ID, '_metatest_data', true))
$md = $default_metadata;
echo '

Name:<br/><' . 'input type="text" name="metadata_field[name]" value="' . esc_attr($md['name']) . '" placeholder="Alex Freeman"/><br/>

';
echo '

Contact:<br/><' . 'input type="text" name="metadata_field[contacts]" value="' . esc_attr($md['contacts']) . '" placeholder="204 999 888" /> <br/>

';
echo '

Link:<br/><' . 'input type="text" name="metadata_field[link]" value="' . esc_attr($md['link']) . '" placeholder="http://best-site.org"/><br/>

';
if (esc_attr($md['type'])=='1') $v1 = 'selected'; else $v1 = '';
if (esc_attr($md['type'])=='2') $v2 = 'selected'; else $v2 = '';
echo '

Type:<br/><select name="metadata_field[type]">
	<option value="1" ' . $v1 . '>Free</option>
	<option value="2" ' . $v2 . '>$</option>
</select><br/>

';
echo '

Email:<br/><' . 'input type="text" name="metadata_field[email]" value="' . esc_attr($md['email']) . '" placeholder="mail@best-site.org"/>

';

// скрытое поле с одноразовым кодом 
wp_nonce_field('metatest_action', 'metatest_nonce'); 

}

function metatest_save($postID) { 

// пришло ли поле наших данных? 
if (!isset($_POST['metadata_field'])) 
return; 

// не происходит ли автосохранение? 
if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
return; 

// не ревизию ли сохраняем? 
if (wp_is_post_revision($postID)) 
return; 

// проверка достоверности запроса 
check_admin_referer('metatest_action', 'metatest_nonce'); 

// коррекция данных 
//$data = sanitize_text_field($_POST['metadata_field']); 
$data = $_POST['metadata_field']; 

// запись 
update_post_meta($postID, '_metatest_data', $data); 
}  

function mds_uninstall () {
delete_post_meta_by_key('_metatest_data');
}

// Change the columns for the edit CPT screen
function change_columns( $cols ) {
  $cols = array(
    'cb'       => '<input type="checkbox" />',
    'title'      => __( 'Clinic name',      'trans' ),
    'name'      => __( 'Author',      'trans' ),
    'type'      => __( 'Account type',      'trans' ),
  );
  return $cols;
}
add_filter( "manage_dentists_posts_columns", "change_columns" );

function custom_columns( $column, $post_id ) {
	$md = get_post_meta($post_id, '_metatest_data', true);
  switch ( $column ) {
    case "title":
      echo get_title( $post_id);
      break;
    case "name":
      echo $md['name'];
      break;
    case "type":
      if ($md['type'] == 2) $type='$';
		else $type = 'free';
      echo $type;
      break;
  }
}

add_action( "manage_posts_custom_column", "custom_columns", 10, 2 );

/*// Make these columns sortable
function sortable_columns() {
  return array(
    'title'      => 'title',
    'name' => 'name',
    'type'     => 'type'
  );
}

add_filter( "manage_edit-dentists_sortable_columns", "sortable_columns" );*/
?>