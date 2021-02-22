<?php
	/**
	 * Bootstrap on Wordpress functions and definitions
	 *
	 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
	 *
	 * @package 	WordPress
	 * @subpackage 	Bootstrap 5.0.0-beta
	 * @autor 		Babobski
	 */

	define('BOOTSTRAP_VERSION', '5.0.0');

	/* ========================================================================================================================

	Add language support to theme

	======================================================================================================================== */
	add_action('after_setup_theme', 'my_theme_setup');
	function my_theme_setup(){
		load_theme_textdomain('wp_babobski', get_template_directory() . '/language');
	}



	/* ========================================================================================================================

	Required external files

	======================================================================================================================== */

	require_once( 'external/bootstrap-utilities.php' );
	require_once( 'external/bs5navwalker.php' );

	/* ========================================================================================================================

	Add html 5 support to wordpress elements

	======================================================================================================================== */

	add_theme_support( 'html5', array(
		'comment-list',
		'search-form',
		'comment-form',
		'gallery',
		'caption',
	) );

	/* ========================================================================================================================

	Theme specific settings

	======================================================================================================================== */

	add_theme_support('post-thumbnails');

	//add_image_size( 'name', width, height, crop true|false );

	register_nav_menus(array('primary' => 'Primary Navigation'));

	

	/* ========================================================================================================================

	Actions and Filters

	======================================================================================================================== */

	add_action( 'wp_enqueue_scripts', 'bootstrap_script_init' );

	add_filter( 'body_class', array( 'BsWp', 'add_slug_to_body_class' ) );



	// function custom_post_type( $args, $post_type ) {
	// 	if ( $post_type == "animal-species" ) {
	// 		$args['rewrite'] = array(
	// 			'slug' => 'animal'
	// 		);
	// 	}
	
	// 	return $args;
	// }
	// add_filter( 'register_post_type_args', 'custom_post_type', 20, 2 );

	/* ========================================================================================================================

	Custom Post Types - include custom post types and taxonomies here e.g.

	e.g. require_once( 'custom-post-types/your-custom-post-type.php' );

	======================================================================================================================== */



	/* ========================================================================================================================

	Scripts

	======================================================================================================================== */

	/**
	 * Add scripts via wp_head()
	 *
	 * @return void
	 * @author Keir Whitaker
	 */
	if ( !function_exists( 'bootstrap_script_init' ) ) {
		function bootstrap_script_init() {

			// Get theme version number (located in style.css)
			$theme = wp_get_theme();

			wp_register_script('bootstrap', get_template_directory_uri() . '/js/bootstrap.bundle.min.js', array( 'jquery' ), BOOTSTRAP_VERSION, true);
			wp_enqueue_script('bootstrap');

			wp_register_script( 'site', get_template_directory_uri() . '/js/site.js', array( 'jquery', 'bootstrap' ), $theme->get( 'Version' ), true );
			wp_enqueue_script( 'site' );

			wp_register_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', array(), BOOTSTRAP_VERSION, 'all' );
			wp_enqueue_style( 'bootstrap' );

			wp_register_style( 'screen', get_template_directory_uri() . '/style.css', array(), $theme->get( 'Version' ), 'screen' );
			wp_enqueue_style( 'screen' );
		}
	}

	/* ========================================================================================================================

	Security & cleanup wp admin

	======================================================================================================================== */

	//remove wp version
	function theme_remove_version() {
		return '';
	}

	add_filter('the_generator', 'theme_remove_version');

	//remove default footer text
	function remove_footer_admin () {
		echo "";
	}

	add_filter('admin_footer_text', 'remove_footer_admin');

	//remove wordpress logo from adminbar
	function wp_logo_admin_bar_remove() {
		global $wp_admin_bar;

		/* Remove their stuff */
		$wp_admin_bar->remove_menu('wp-logo');
	}

	add_action('wp_before_admin_bar_render', 'wp_logo_admin_bar_remove', 0);

	// Remove default Dashboard widgets
	if ( !function_exists( 'disable_default_dashboard_widgets' ) ) {
		function disable_default_dashboard_widgets() {

			//remove_meta_box('dashboard_right_now', 'dashboard', 'core');
			remove_meta_box('dashboard_activity', 'dashboard', 'core');
			remove_meta_box('dashboard_recent_comments', 'dashboard', 'core');
			remove_meta_box('dashboard_incoming_links', 'dashboard', 'core');
			remove_meta_box('dashboard_plugins', 'dashboard', 'core');
	
			remove_meta_box('dashboard_quick_press', 'dashboard', 'core');
			remove_meta_box('dashboard_recent_drafts', 'dashboard', 'core');
			remove_meta_box('dashboard_primary', 'dashboard', 'core');
			remove_meta_box('dashboard_secondary', 'dashboard', 'core');
		}
	}
	add_action('admin_menu', 'disable_default_dashboard_widgets');

	remove_action('welcome_panel', 'wp_welcome_panel');

	// Disable the emoji's
	if ( !function_exists( 'disable_emojis' ) ) {
		function disable_emojis() {
			remove_action('wp_head', 'print_emoji_detection_script', 7);
			remove_action('admin_print_scripts', 'print_emoji_detection_script');
			remove_action('wp_print_styles', 'print_emoji_styles');
			remove_action('admin_print_styles', 'print_emoji_styles');
			remove_filter('the_content_feed', 'wp_staticize_emoji');
			remove_filter('comment_text_rss', 'wp_staticize_emoji');
			remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

			// Remove from TinyMCE
			add_filter('tiny_mce_plugins', 'disable_emojis_tinymce');
		}
	}
	add_action('init', 'disable_emojis');


	// function custom_post_type() {
	// 	$labels = array(
	// 		'name'                  => _x( 'TIL', 'Post type general name', 'til' ),
	// 		'singular_name'         => _x( 'til', 'Post type singular name', 'til' ),
	// 		'menu_name'             => _x( 'tils', 'Admin Menu text', 'til' ),
	// 		'name_admin_bar'        => _x( 'til', 'Add New on Toolbar', 'til' ),
	// 		'add_new'               => __( 'Add New', 'til' ),
	// 		'add_new_item'          => __( 'Add New til', 'til' ),
	// 		'new_item'              => __( 'New til', 'til' ),
	// 		'edit_item'             => __( 'Edit til', 'til' ),
	// 		'view_item'             => __( 'View til', 'til' ),
	// 		'all_items'             => __( 'All tils', 'til' ),
	// 		'search_items'          => __( 'Search tils', 'til' ),
	// 		'parent_item_colon'     => __( 'Parent tils:', 'til' ),
	// 		'not_found'             => __( 'No tils found.', 'til' ),
	// 		'not_found_in_trash'    => __( 'No tils found in Trash.', 'til' ),
	// 		'featured_image'        => _x( 'til Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'til' ),
	// 		'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'til' ),
	// 		'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'til' ),
	// 		'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'til' ),
	// 		'archives'              => _x( 'til archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'til' ),
	// 		'insert_into_item'      => _x( 'Insert into til', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'til' ),
	// 		'uploaded_to_this_item' => _x( 'Uploaded to this til', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'til' ),
	// 		'filter_items_list'     => _x( 'Filter tils list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'til' ),
	// 		'items_list_navigation' => _x( 'tils list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'til' ),
	// 		'items_list'            => _x( 'tils list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'til' ),
	// 	);     
	// 	$args = array(
	// 		'labels'             => $labels,
	// 		'description'        => 'til custom post type.',
	// 		'public'             => true,
	// 		'publicly_queryable' => true,
	// 		'show_ui'            => true,
	// 		'show_in_menu'       => true,
	// 		'query_var'          => true,
	// 		'rewrite'            => array( 'slug' => 'til' ),
	// 		'capability_type'    => 'post',
	// 		'has_archive'        => true,
	// 		'hierarchical'       => false,
	// 		'menu_position'      => 20,
	// 		'supports'           => array( 'title', 'editor', 'author', 'thumbnail' ),
	// 		'taxonomies'         => array( 'category', 'post_tag' ),
	// 		'show_in_rest'       => true
	// 	);
		  
	// 	register_post_type( 'til', $args );
	// }
	// add_action('init', 'custom_post_type');



	// Filter out the tinymce emoji plugin.
	function disable_emojis_tinymce($plugins) {
		if (is_array($plugins)) {
			return array_diff($plugins, array('wpemoji'));
		} else {
			return array();
		}
	}

	add_action('admin_head', 'custom_logo_guttenberg');

	if ( !function_exists( 'custom_logo_guttenberg' ) ) {
		function custom_logo_guttenberg() {
			echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('stylesheet_directory').
			'/css/admin-custom.css" />';
		}
	}

	/* ========================================================================================================================

	Custom login

	======================================================================================================================== */

	// Add custom css
	if ( !function_exists( 'my_custom_login' ) ) {
		function my_custom_login() {
			echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('stylesheet_directory') . '/css/custom-login-style.css" />';
		}
	}
	add_action('login_head', 'my_custom_login');

	// Link the logo to the home of our website
	if ( !function_exists( 'my_login_logo_url' ) ) {
		function my_login_logo_url() {
			return get_bloginfo( 'url' );
		}
	}
	add_filter( 'login_headerurl', 'my_login_logo_url' );

	// Change the title text
	if ( !function_exists( 'my_login_logo_url_title' ) ) {
	function my_login_logo_url_title() {
		return get_bloginfo( 'name' );
	}
	}
	add_filter( 'login_headertext', 'my_login_logo_url_title' );
	

	/* ========================================================================================================================

	Comments

	======================================================================================================================== */

	/**
	 * Custom callback for outputting comments
	 *
	 * @return void
	 * @author Keir Whitaker
	 */
	if (!function_exists( 'bootstrap_comment' )) {
		function bootstrap_comment( $comment, $args, $depth ) {
			$GLOBALS['comment'] = $comment;
			?>
			<?php if ( $comment->comment_approved == '1' ): ?>
			<li class="row">
				<div class="col-4 col-md-2">
					<?php echo get_avatar( $comment ); ?>
				</div>
				<div class="col-8 col-md-10">
					<h4><?php comment_author_link() ?></h4>
					<time><a href="#comment-<?php comment_ID() ?>" pubdate><?php comment_date() ?> at <?php comment_time() ?></a></time>
					<?php comment_text() ?>
				</div>
			<?php endif;
		}
	}
