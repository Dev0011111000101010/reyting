<?php
/**
 * referendum functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package referendum
 */

if ( ! function_exists( 'referendum_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function referendum_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on referendum, use a find and replace
		 * to change 'referendum' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'referendum', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'referendum' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'referendum_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'referendum_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function referendum_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'referendum_content_width', 640 );
}

add_action( 'after_setup_theme', 'referendum_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function referendum_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Filter sidebar', 'referendum' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'referendum' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		)
	);
}

add_action( 'widgets_init', 'referendum_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function referendum_scripts() {
	wp_enqueue_script( 'select-js', 'https://cdnjs.cloudflare.com/ajax/libs/tarekraafat-autocomplete.js/10.2.6/autoComplete.min.js', array('jquery', 'bootstrap-js') );

	wp_enqueue_style( 'bootstrap-css', get_template_directory_uri() . '/assets/css/bootstrap.min.css' );
	wp_enqueue_style( 'country-select', get_template_directory_uri() . '/assets/css/countrySelect.css' );

	wp_enqueue_style( 'select-css', 'https://cdnjs.cloudflare.com/ajax/libs/tarekraafat-autocomplete.js/10.2.6/css/autoComplete.min.css' );

	wp_enqueue_script( 'bootstrap-js', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array('jquery') );
	wp_enqueue_script( 'country-select', get_template_directory_uri() . '/assets/js/countrySelect.min.js', array( 'jquery' ) );

	wp_enqueue_script( 'script-js', get_template_directory_uri() . '/assets/js/script.js', array(
		'country-select',
		'select-js',
	) );

	wp_enqueue_style( 'referendum-css', get_stylesheet_uri(), array() );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

add_action( 'wp_enqueue_scripts', 'referendum_scripts' );

function admin_styl() {
	wp_enqueue_style( 'admin-css', get_template_directory_uri() . '/admin-styles.css' );
}

add_action('admin_enqueue_scripts', 'admin_styl');

function add_additional_class_on_li( $classes, $item, $args ) {
	if ( isset( $args->add_li_class ) ) {
		$classes[] = $args->add_li_class;
	}

	return $classes;
}

add_filter( 'nav_menu_css_class', 'add_additional_class_on_li', 1, 3 );

function add_menu_link_class( $atts, $item, $args ) {
	if ( $args->theme_location == 'menu-1' ) {
		$atts['class'] = 'nav-link';
	}

	return $atts;
}

add_filter( 'nav_menu_link_attributes', 'add_menu_link_class', 1, 3 );

remove_action( 'set_comment_cookies', 'wp_set_comment_cookies', 10, 3 );

remove_filter( 'the_content', 'rcl_concat_post_meta', 10 );

remove_filter( 'the_content', 'rcl_author_info', 70 );

register_taxonomy( 'countries', [ 'post' ], [
	'label'        => '',
	// определяется параметром $labels->name
	'labels'       => [
		'name'          => 'Страны',
		'singular_name' => 'Страны',
		'all_items'     => 'Все Страны',
		'view_item '    => 'Просмотр',
		'edit_item'     => 'Редактировать Страну',
		'add_new_item'  => 'Добавить страну',
		'menu_name'     => 'Страны',
	],
	'description'  => '',
	// описание таксономии
	'public'       => true,
	'hierarchical' => true,

	'rewrite'           => true,
	'capabilities'      => array(),
	'meta_box_cb'       => null,
	// html метабокса. callback: `post_categories_meta_box` или `post_tags_meta_box`. false — метабокс отключен.
	'show_admin_column' => false,
	// авто-создание колонки таксы в таблице ассоциированного типа записи. (с версии 3.5)
	'show_in_rest'      => true,
	// добавить в REST API
] );

//add_filter( 'rcl_public_form_primary_buttons', function ( $data ) {
//	$data['publish']['class'] = 'btn btn-blue';
//
//	return $data;
//} );

//require_once RCL_PATH . 'classes/class-rcl-users-list.php';

require "functions/taxonomies.php";

//require "functions/field-group.php";


//add_shortcode( 'referendum_userlist', 'referendum_userlist' );
//function referendum_userlist( $atts ) {
//	global $rcl_user, $rcl_users_set, $user_ID;
//
//
//	$users = new Referendum_Users_List( $atts );
//
//	$count_users = false;
//
//	if ( ! isset( $atts['number'] ) ) {
//
//		$count_users = $users->count();
//
//		$id_pager = ( $users->id ) ? 'rcl-users-' . $users->id : 'rcl-users';
//
//		$pagenavi = new Rcl_PageNavi( $id_pager, $count_users, array( 'in_page' => $users->query['number'] ) );
//
//		$users->query['offset'] = $pagenavi->offset;
//	}
//
//	$timecache = ( $user_ID && $users->query['number'] == 'time_action' ) ? rcl_get_option( 'timeout', 600 ) : 0;
//
//	$rcl_cache = new Rcl_Cache( $timecache );
//
//	if ( $rcl_cache->is_cache ) {
//		if ( isset( $users->id ) && $users->id == 'rcl-online-users' ) {
//			$string = json_encode( $users );
//		} else {
//			$string = json_encode( $users->query );
//		}
//
//		$file = $rcl_cache->get_file( $string );
//
//		if ( ! $file->need_update ) {
//
//			$users->remove_filters();
//
//			return $rcl_cache->get_cache();
//		}
//	}
//
//	$usersdata = $users->get_users();
//
//	$userlist = $users->get_filters( $count_users );
//
//	$userlist .= '<div class="rcl-userlist"><div class="container">';
//
//	if ( ! $usersdata ) {
//		$userlist .= rcl_get_notice( [ 'text' => __( 'Users not found', 'wp-recall' ) ] );
//	} else {
//
//		if ( ! isset( $atts['number'] ) && $pagenavi->in_page ) {
//			$userlist .= $pagenavi->pagenavi();
//		}
//
//		$userlist .= '<div class="userlist ' . $users->template . '-list">';
//
//		$rcl_users_set = $users;
//
//		foreach ( $usersdata as $rcl_user ) {
//			$users->setup_userdata( $rcl_user );
//			$userlist .= rcl_get_include_template( 'user-' . $users->template . '.php' );
//		}
//
//		$userlist .= '</div>';
//
//		if ( ! isset( $atts['number'] ) && $pagenavi->in_page ) {
//			$userlist .= $pagenavi->pagenavi();
//		}
//	}
//
//	$userlist .= '</div></div>';
//
//	$users->remove_filters();
//
//	if ( $rcl_cache->is_cache ) {
//		$rcl_cache->update_cache( $userlist );
//	}
//
//	return $userlist;
//}

add_filter( 'users_search_form_rcl', function ( $data ) {
	$newdata = '<div class="container">' . $data . '</div>';

	return $newdata;
} );

add_filter( 'profile_options_rcl', function ( $e ) {
	$tbtn = wptelegram_login( [
		"show_if_user_is" => "logged_in"
	], false );
	if ( $tbtn ) {
		$e .= "<div class='row no-gutters justify-content-between link-telegram'>";
		$e .= "<b>Привязать Telegram</b>" . $tbtn . "</div>";
	}

	return $e;
} );

function referendum_get_author_block() {
	global $post;

	$content = "<div id=block_author-rcl>";
	$content .= "<h3>" . __( 'Publication author', 'wp-recall' ) . "</h3>";

	if ( function_exists( 'rcl_add_userlist_follow_button' ) ) {
		add_filter( 'rcl_user_description', 'rcl_add_userlist_follow_button', 90 );
	}

	$content .= rcl_get_userlist( array(
		'template' => 'rows',
		'orderby'  => 'display_name',
		'include'  => $post->post_author,
		'filter'   => 0,
		'data'     => 'rating_total,description,posts_count,user_registered,comments_count'
	) );

	if ( function_exists( 'rcl_add_userlist_follow_button' ) ) {
		remove_filter( 'rcl_user_description', 'rcl_add_userlist_follow_button', 90 );
	}

	$content .= "<div class='user-contactinfo'>";
	global $rcl_user;
	$fb_link       = get_user_meta( $rcl_user->ID, 'ssylka_na_fb_akkaunt_39', true );
	$bitchute_link = get_user_meta( $rcl_user->ID, 'ssylka_na_bitchute_12', true );
	$youtube_link  = get_user_meta( $rcl_user->ID, 'ssylka_na_youtube_14', true );
	$tg_link       = get_user_meta( $rcl_user->ID, 'ssylka_na_telegram_77', true );
	?>
	<?php if ( $fb_link ) {
		$content .= "<div>
                <b>Facebook автора</b> <a rel='nofollow noopener' href='$fb_link'>$fb_link</a>
            </div>";
	} ?>
	<?php if ( $bitchute_link ) {
		$content .= "<div>
                <b>Bitchute автора</b> <a rel='nofollow noopener' href='$bitchute_link'>$bitchute_link</a>
            </div>";
	} ?>
	<?php if ( $youtube_link ) {
		$content .= "<div>
                <b>Youtube автора</b> <a rel='nofollow noopener' href='$youtube_link'>$youtube_link</a>
            </div>";
	} ?>
	<?php if ( $tg_link ) {
		$content .= "<div>
                <b>Телеграм автора</b> <a rel='nofollow noopener' href='$tg_link'>$tg_link</a>
            </div>";
	}

	$content .= "</div>";

	return $content;
}

add_filter( 'rcl_tab', 'edit_profile_tab_data' );
function edit_profile_tab_data( $data ) {
	global $user_ID, $rcl_office;
	if ( $data['id'] != 'profile' ) {
		return $data;
	}
	if ( $rcl_office != $user_ID ) {
		$data['content'][0]['callback'] = array(
			'name' => 'my_custom_function',
			'args' => @array( $arg_1, $arg_2 ),
		);
		$data['public'] = 1;
	}

	return $data;
}

function my_custom_function( $arg_1, $arg_2 ) {
	global $user_ID, $user_LK;
	if ( $user_ID !== $user_LK ) {
		$returninfo = load_template_part( 'template-parts/lawyer', 'profile' );

		return $returninfo;

	}

	return rcl_tab_profile_content( $arg_1, $arg_2 );
}


function load_template_part( $template_name, $part_name = null ) {
	ob_start();
	get_template_part( $template_name, $part_name );
	$var = ob_get_contents();
	ob_end_clean();

	return $var;
}

function sertificateActivity( $sertificate ) {
	ob_start();
	if ( $sertificate['active'] && $sertificate['issuedate'] ) {
		esc_html_e( 'Дійсне, дата видачі: ', 'referendum' );
		echo $sertificate['issuedate'];
	} elseif ( $sertificate['validstart'] ) {
		esc_html_e( 'Не дійсне, дійсне було з ', 'referendum' );
		echo $sertificate['validstart'];
		echo "&nbsp;";
		esc_html_e( 'до', 'referendum' );
		echo "&nbsp;" . $sertificate['validend'];
	} else {
		esc_html_e( 'Не отримувалося' );
	}
	$var = ob_get_contents();
	ob_end_clean();

	return $var;
}

function justiceSectorsWork( $item ) {
	ob_start();
	echo $item['practise'];
	if ( $item['totalcount'] ) {
		echo ' (' . $item['totalcount'] . ')';
	}
	$var = ob_get_contents();
	ob_end_clean();

	return $var;
}

define( 'FALSY', __( 'Практика відсутня', 'referendum' ) );
define( 'TRUTHY', __( 'Практикує', 'referendum' ) );

function professionalSector( $item, $falsy = FALSY ) {
	ob_start();
	if ( is_array( $item ) && isset( $item[0] ) ) {
		foreach ( $item as $p ) {
			echo $p['article'] . '&nbsp;(' . $p['totalcount'] . ')' . '<br>';

			echo '<b>' . __( 'По судам:', 'referendum' ) . '</b><br>';
			foreach ( $p['courts'] as $court => $count ) {
				echo $court . ' (' . $count . ') <br>';
			}
		}
	} else {
		echo $falsy;
	}
	$var = ob_get_contents();
	ob_end_clean();

	return $var;
}

// обновление метаданных пользователя
add_action( 'user_register', 'my_user_registration' );
function my_user_registration( $user_id ) {
	$user = new WP_User( $user_id );

	$p = wp_insert_post( array(
		'post_title'  => $user->user_nicename,
		'post_type'   => 'users_filter',
		'post_status' => 'publish'
	) );

	update_post_meta( $p, '_linked_user', $user_id );
}

add_action( 'delete_user', 'user_deletion' );
function user_deletion( $user_id ) {
	$p     = new WP_Query( array(
		'post_type'  => 'users_filter',
		'meta_key'   => '_linked_user',
		'meta_value' => $user_id,
		'fields'     => 'ids',
	) );
	$spost = $p->posts[0];
	wp_delete_post( $spost );
}

function countPriceSegment( $price ) {
	ob_start();

	switch ( $price['price'] ) {
		case ($price['price'] == 0):
			echo "Безоплатно ";
			break;
		case ($price['price'] < 11):
			echo "Дешево (1~10$/година) ";
			break;
		case ($price['price'] < 31):
			echo "Середні ціни (10~30$/година) ";
			break;
		case ($price['price'] > 30):
			echo "VIP-сегмент ";
			break;
		default:
			echo "Безкоштовно ";
	}

	if ( $price['why'] ) {
		echo $price['why'];
	}

	$var = ob_get_contents();
	ob_end_clean();

	return $var;
}

function getUserPage($uid) {
	$p = new WP_Query( array(
		'post_type'  => 'users_filter',
		'meta_key'   => '_linked_user',
		'meta_value' => $uid,
		'fields'     => 'ids',
	) );

	$userpageid = $p->posts[0];

	return $userpageid;
}

add_filter('body_class', function ($classes) {
	if($_GET['professional'] == 'true') {
		$classes[] = 'professional-search';
	}

	return $classes;
});

add_filter('user_search_columns', 'user_search_columns_bd' , 10, 3);

function user_search_columns_bd($search_columns){

	if(!in_array('display_name', $search_columns)){
		$search_columns[] = 'display_name';
	}
	return $search_columns;
}


//require ABSPATH . '/vendor/autoload.php';
//
////$files        = scandir( 'E:\OSPanel\domains\windows.parser\converted10k' );
//$importedfile = __DIR__ . '/documents_part1_0.xlsx';
//
//use PhpOffice\PhpSpreadsheet\Spreadsheet;
//
//$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load( $importedfile );
//$sheetData   = $spreadsheet->getActiveSheet()->toArray();
//
//function translit( $value ) {
//	$converter = array(
//		'а' => 'a',
//		'б' => 'b',
//		'в' => 'v',
//		'г' => 'g',
//		'д' => 'd',
//		'е' => 'e',
//		'ё' => 'e',
//		'ж' => 'zh',
//		'з' => 'z',
//		'и' => 'i',
//		'й' => 'i',
//		'к' => 'k',
//		'л' => 'l',
//		'м' => 'm',
//		'н' => 'n',
//		'о' => 'o',
//		'п' => 'p',
//		'р' => 'r',
//		'с' => 's',
//		'т' => 't',
//		'у' => 'u',
//		'ф' => 'f',
//		'х' => 'kh',
//		'ц' => 'c',
//		'ч' => 'ch',
//		'ш' => 'sh',
//		'щ' => 'shch',
//		'ь' => '',
//		'ы' => 'y',
//		'ъ' => '',
//		'є' => 'ie',
//		'ю' => 'yu',
//		'я' => 'ya',
//		'і' => 'i',
//		'ї' => 'i',
//
//		'А' => 'A',
//		'Б' => 'B',
//		'В' => 'V',
//		'Г' => 'G',
//		'Д' => 'D',
//		'Е' => 'E',
//		'Є' => 'Ye',
//		'Ж' => 'Zh',
//		'З' => 'Z',
//		'И' => 'I',
//		'Й' => 'Y',
//		'К' => 'K',
//		'Л' => 'L',
//		'М' => 'M',
//		'Н' => 'N',
//		'О' => 'O',
//		'П' => 'P',
//		'Р' => 'R',
//		'С' => 'S',
//		'Т' => 'T',
//		'У' => 'U',
//		'Ф' => 'F',
//		'Х' => 'Kh',
//		'Ц' => 'C',
//		'Ч' => 'Ch',
//		'Ш' => 'Sh',
//		'Щ' => 'Shch',
//		'Ь' => '',
//		'Ы' => 'Y',
//		'Ъ' => '',
//		'Э' => 'E',
//		'Ю' => 'Yu',
//		'Я' => 'Ya',
//		'I' => 'I',
//		'Ї' => 'Yi'
//	);
//
//	$value = strtr( $value, $converter );
//
//	return $value;
//}
//
//function userCreation($lawyer ) {
//	preg_match( '/[А-ЯІIЇЄ]\.[А-ЯІIЇЄ]/u', $lawyer, $firstname );
//	preg_match( '/[А-ЯІIЇЄ][а-яіiїє|А-ЯІIЇЄ]+/u', $lawyer, $lastname );
//	$username = mb_strtolower( translit( $lastname[0] ) ) . rand( 0, 9 );
//	$email    = translit( $lastname[0] ) . '@mentorsflow.com';
//	$uid      = wp_insert_user( array(
//		'first_name'    => $firstname[0],
//		'last_name'     => $lastname[0],
//		'user_email'    => $email,
//		'user_login'    => $username,
//		'user_nicename' => $username,
//		'display_name'  => $lawyer,
//	) );
//
//	$user = new WP_User( $uid );
//
//	$user->add_role( 'lawyer' );
//	$user->remove_role( 'author' );
//
//	return $uid;
//}
//
//function writeJusticeKind($userpageid, $justice_kind) {
//	$justice_kinds = get_post_meta( $userpageid, 'justice_kind' );
//	$arr = ['1'=>'civil', '2'=>'criminal', '3'=>'commercial'];
//
//	if ( ! $justice_kinds[ $justice_kind ] ) {
//		update_post_meta( $userpageid, 'justice_kind', $justice_kind );
//	}
//
//	if($arr[$justice_kind]) {
//		$current = (int) get_post_meta($userpageid, 'justice_'.$justice_kind.'_count', true);
//		if(!$current) {
//			$current = 1;
//		}else {
//			$current+=1;
//		}
//		update_post_meta($userpageid, 'justice_'.$justice_kind.'_count', $current);
//	}
//}
//
//foreach ( $sheetData as $field ) {
//	$startTime = microtime(true); //get time in micro seconds(1 millionth)
//	$lawyer       = $field[12];
//	$casenum      = $field[5];
//	$justice_kind = $field[3];
//
//	if ( ! $lawyer ) {
//		continue;
//	}
//
//	if ( $casenum === 'cause_num' ) {
//		continue;
//	}
//
//	$args = array(
//		'search'        => "$lawyer", // or login or nicename in this example
//		'search_fields' => array( 'display_name' )
//	);
//
//	$users = new WP_User_Query( $args );
//
//	$users = $users->get_results();
//
//	if ( !isset( $users[0] ) ) {
//		$uid = userCreation($lawyer);
//	}else {
//		$uid = $users[0]->ID;
//	}
//
//	$userpageid = getUserPage($uid);
//
//	writeJusticeKind($userpageid, $justice_kind);
//
//	$wpdb->insert( 'lawyers_cases', [ 'lawyer_id' => $uid, 'scase' => $casenum ] );
//
//	var_dump($lawyer);
//
//	$endTime = microtime(true);
//}
//
//echo "seconds to execute:". ($endTime-$startTime)*100000;

//function delete_all_terms(){
//	$taxonomy_name = 'decisions_branch';
//	$terms = get_terms( array(
//		'taxonomy' => $taxonomy_name,
//		'hide_empty' => false
//	) );
//	foreach ( $terms as $term ) {
//		wp_delete_term($term->term_id, $taxonomy_name);
//	}
//}
//add_action( 'wp_head', 'delete_all_terms' );

//add_filter('sf_input_object_pre', function ($item) {
//	var_dump($item);
//	return $item;
//});

function save_this_search() {
	global $wpdb;

//	$my_user_id = get_current_user_id();
	$search_parameter = $_REQUEST['search_parameter'];

	$wpdb->insert(
		'search_queries',
		array(
			'search_parameter' => $search_parameter,
		),
	);

	/*echo 'The search name: '. $search_name;
	echo 'My user ID: '. $my_user_id;
	echo 'Id: '. $id;*/

	die();
}
add_action('wp_ajax_save_this_search', 'save_this_search');
add_action('wp_ajax_nopriv_save_this_search', 'save_this_search');