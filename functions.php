<?php

/**
 *
 *
 * Example code for WordPress Melbourne meetup to test pre_get_posts hook
 *
 *
 */


/**
 * Add an action when WP Admin initialises to call the function my_create_custom_post_types() to register our Custom Post Type
 */
function my_create_custom_post_types() {

	$types = array(
		// Where the magic happens
		array( 'the_type' => 'movie',
			'single' => 'Movie',
			'plural' => 'Movies',
			'rewrite' => 'movie', )
	);

	foreach ( $types as $type ) {

		$the_type = $type['the_type'];
		$single = $type['single'];
		$plural = $type['plural'];
		$rewrite = $type['rewrite'];

		$labels = array(
			'name' => _x( $plural, 'post type general name' ),
			'singular_name' => _x( $single, 'post type singular name' ),
			'add_new' => _x( 'Add New', $single ),
			'add_new_item' => __( 'Add New '. $single ),
			'edit_item' => __( 'Edit '.$single ),
			'new_item' => __( 'New '.$single ),
			'view_item' => __( 'View '.$single ),
			'search_items' => __( 'Search '.$plural ),
			'not_found' =>  __( 'No '.$plural.' found' ),
			'not_found_in_trash' => __( 'No '.$plural.' found in Trash' )
		);

		$args = array(
			'labels' => $labels,
			'public' => true,
			'has_archive' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_position' => 5,
			'rewrite' => array( 'slug' => $rewrite ),
			'supports' => array( 'title','editor', 'author', 'thumbnail','custom-fields','excerpt' )
		);

		register_post_type( $the_type, $args );

	}

}
add_action( 'init', 'my_create_custom_post_types' );


/**
 * Add an action when WP Admin initialises to call the function my_create_cpt_taxonomies() which registers our CPT Taxonomies
 */
 function my_create_cpt_taxonomies() {

	$cptname = "movie";
	$types = array(
		// Where the magic happens
		array('the_type' => 'genre',
			'single' => 'Genre',
			'plural' => 'Genres'),
		array('the_type' => 'actor',
			'single' => 'Actor',
			'plural' => 'Actors'),
		array('the_type' => 'director',
			'single' => 'Director',
			'plural' => 'Directors')
	);

	foreach ($types as $type) {

		$the_type = $type['the_type'];
		$single = $type['single'];
		$plural = $type['plural'];


		$labels = array(
			'name' => _x( $plural, 'taxonomy general name' ),
			'singular_name' => _x( $single, 'taxonomy singular name' ),
			'search_items' =>  __( 'Search ' . $plural ),
			'all_items' => __( 'All ' . $plural ),
			'parent_item' => __( 'Parent ' . $single ),
			'parent_item_colon' => __( 'Parent ' . $single.':' ),
			'edit_item' => __( 'Edit ' . $single ), 
			'update_item' => __( 'Update ' . $single ),
			'add_new_item' => __( 'Add New ' . $single ),
			'new_item_name' => __( 'New ' . $single ),
			'menu_name' => __( $single ),
		); 	

		register_taxonomy( $the_type, array($cptname), array(
			'hierarchical' => true,
			'labels' => $labels,
			'update_count_callback' => '_update_post_term_count',
			'rewrite' => true
		));

	}

}
add_action( 'init', 'my_create_cpt_taxonomies', 0 );


/**
 * Add an action and filter to customise the display when viewing the list of Custom Post Types
 */
add_action( 'manage_posts_custom_column',  'my_home_custom_columns' );
add_filter( 'manage_edit-movie_columns', 'my_home_edit_columns' );
 
function my_home_edit_columns($columns){
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => 'Title',
		'genre' => 'Genre',
		'actor' => 'Actor',
		'director' => 'Director',
		'date' => 'Date'
	);
	
	return $columns;
}

function my_home_custom_columns($column){
	global $post;
	
	switch ($column) {
		case 'genre':
			echo get_the_term_list($post->ID, 'genre', '', ', ','');
			break;
		case 'actor':
			echo get_the_term_list($post->ID, 'actor', '', ', ','');
			break;
		case 'director':
			echo get_the_term_list($post->ID, 'director', '', ', ','');
			break;
        
	}
}


/**
 * Adjust the main query using pre_get_posts, based on the page being displayed
 */
function my_pre_get_posts( $query ) {
	if ( $query->is_main_query() && is_home() && !is_admin() ) {
		// Display only posts that belong to a certain Category
		$query->set( 'category_name', 'fatuity' );
		// Display only 3 posts per page
		$query->set( 'posts_per_page', '3' );
		return;
	}
	if ( $query->is_main_query() && is_post_type_archive( 'movie' ) && !is_admin() ){
		// Display 3 posts for a custom post type called 'movie'
		//$query->set( 'posts_per_page', '	3' );

		// Display only posts from a certain taxonomies
		$query->set( 'tax_query', array(
			array(
				'taxonomy' => 'genre',
				'field' => 'slug',
				'terms' => array ( 'fantasy', 'sci-fi' )
			)
		) );
		return;
	}
}
add_action( 'pre_get_posts', 'my_pre_get_posts' );
