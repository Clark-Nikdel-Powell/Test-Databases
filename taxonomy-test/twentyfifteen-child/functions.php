<?php

// Enqueue Parent Styles
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'child-style', get_stylesheet_uri(), array( 'parent-style' ) );
}

/**
 * Create a taxonomy
 *
 * @uses  Inserts new taxonomy object into the list
 * @uses  Adds query vars
 *
 * @param string  Name of taxonomy object
 * @param array|string  Name of the object type for the taxonomy object.
 * @param array|string  Taxonomy arguments
 * @return null|WP_Error WP_Error if errors, otherwise null.
 */
function register_movie_genre_taxonomy() {

	$labels = array(
		'name'					=> _x( 'Genres', 'Taxonomy plural name' ),
		'singular_name'			=> _x( 'Genre', 'Taxonomy singular name' ),
		'search_items'			=> __( 'Search Genres' ),
		'popular_items'			=> __( 'Popular Genres' ),
		'all_items'				=> __( 'All Genres' ),
		'parent_item'			=> __( 'Parent Genre' ),
		'parent_item_colon'		=> __( 'Parent Genre' ),
		'edit_item'				=> __( 'Edit Genre' ),
		'update_item'			=> __( 'Update Genre' ),
		'add_new_item'			=> __( 'Add New Genre' ),
		'new_item_name'			=> __( 'New Genre Name' ),
		'add_or_remove_items'	=> __( 'Add or remove Genres' ),
		'menu_name'				=> __( 'Genre' ),
	);

	$args = array(
		'labels'            => $labels,
		'public'            => true,
		'show_in_nav_menus' => true,
		'show_admin_column' => false,
		'hierarchical'      => true,
		'show_tagcloud'     => true,
		'show_ui'           => true,
		'query_var'         => true,
		'rewrite'           => true,
		'query_var'         => true,
		'capabilities'      => array(),
	);

	register_taxonomy( 'genre', array( 'movie' ), $args );
}

add_action( 'init', 'register_movie_genre_taxonomy' );


/**
* Registers a new post type
* @uses $wp_post_types Inserts new post type object into the list
*
* @param string  Post type key, must not exceed 20 characters
* @param array|string  See optional args description above.
* @return object|WP_Error the registered post type object, or an error object
*/
function register_movies_cpt() {

	$labels = array(
		'name'                => __( 'Movies' ),
		'singular_name'       => __( 'Movie' ),
		'add_new'             => _x( 'Add New Movie' ),
		'add_new_item'        => __( 'Add New Movie' ),
		'edit_item'           => __( 'Edit Movie' ),
		'new_item'            => __( 'New Movie' ),
		'view_item'           => __( 'View Movie' ),
		'search_items'        => __( 'Search Movies' ),
		'not_found'           => __( 'No Movies found' ),
		'not_found_in_trash'  => __( 'No Movies found in Trash' ),
		'parent_item_colon'   => __( 'Parent Movie:' ),
		'menu_name'           => __( 'Movies' ),
	);

	$args = array(
		'labels'              => $labels,
		'hierarchical'        => false,
		'description'         => 'description',
		'taxonomies'          => array(),
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => null,
		'menu_icon'           => null,
		'show_in_nav_menus'   => true,
		'publicly_queryable'  => true,
		'exclude_from_search' => false,
		'has_archive'         => true,
		'query_var'           => true,
		'can_export'          => true,
		'rewrite'             => true,
		'capability_type'     => 'post',
		'taxonomies'          => array('genre'),
		'supports'            => array(
			'title', 'editor', 'author', 'thumbnail',
			'excerpt','revisions', 'page-attributes', 'post-formats'
			)
	);

	register_post_type( 'movie', $args );
}

add_action( 'init', 'register_movies_cpt' );


// Custom walker for the movie genres
class Genres_Walker extends Walker_Category {
	function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
	$pad = str_repeat(' ', $depth * 3);

	/** This filter is documented in wp-includes/category-template.php */
	$cat_name = apply_filters( 'list_cats', $category->name, $category );

	$output .= "\t<option class=\"level-$depth\" value=\"".$category->slug."\"";
	if ( $category->slug == $args['selected'] )
		$output .= ' selected="selected"';
	$output .= '>';
	$output .= $pad.$cat_name;
	if ( $args['show_count'] )
		$output .= '  ('. number_format_i18n( $category->count ) .')';
	$output .= "\n";
	}
}
