<?php
add_action( 'init', 'register_post_types' );
function register_post_types() {
	register_post_type( 'users_filter', [
		'labels'            => [
			'name'          => 'Юзеры', // основное название для типа записи
			'singular_name' => 'Юзеры', // название для одной записи этого типа
		],
		'public'            => true,
	] );
	register_post_type( 'court_decisions', [
		'label'         => null,
		'labels'        => [
			'name'          => 'Решения суда', // основное название для типа записи
			'singular_name' => 'Решения суда', // название для одной записи этого типа
			'add_new'       => 'Добавить Решение', // для добавления новой записи
			'add_new_item'  => 'Добавление Решение', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'     => 'Редактирование Решения', // для редактирования типа записи
			'new_item'      => 'Новое Решение', // текст новой записи
			'view_item'     => 'Смотреть Решения', // для просмотра записи этого типа.
			'search_items'  => 'Искать Решения', // для поиска по этим типам записи
			'menu_name'     => 'Решения суда', // название меню
		],
		'description'   => '',
		'public'        => true,
		'show_in_menu'  => null,
		// показывать ли в меню адмнки
		'show_in_rest'  => null,
		// добавить в REST API. C WP 4.7
		'rest_base'     => null,
		// $post_type. C WP 4.7
		'menu_position' => null,
		'menu_icon'     => null,
		'hierarchical'  => false,
		'supports'      => [ 'title', 'editor' ],
		// 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
		'taxonomies'    => [],
		'has_archive'   => false,
		'rewrite'       => true,
		'query_var'     => true,
	] );
}

// хук для регистрации
add_action( 'init', 'create_taxonomy' );
function create_taxonomy() {

	register_taxonomy( 'decisions_branch', [ 'users_filter' ], [
		'label'        => '',
		// определяется параметром $labels->name
		'labels'       => [
			'name'          => 'Категория дела',
			'singular_name' => 'Категория дела',
			'menu_name'     => 'Категория дел',
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
		'show_in_rest'      => null,
		// добавить в REST API
		'rest_base'         => null,
		// $taxonomy
		// '_builtin'              => false,
		//'update_count_callback' => '_update_post_term_count',
	] );

	register_taxonomy( 'court_region_name', [ 'court_decisions' ], [
		'label'        => '',
		// определяется параметром $labels->name
		'labels'       => [
			'name'          => 'Суд',
			'singular_name' => 'Суд',
			'menu_name'     => 'Суды',
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
		'show_in_rest'      => null,
		// добавить в REST API
		'rest_base'         => null,
		// $taxonomy
		// '_builtin'              => false,
		//'update_count_callback' => '_update_post_term_count',
	] );
	register_taxonomy( 'court_region', [ 'court_decisions' ], [
		'label'        => '',
		// определяется параметром $labels->name
		'labels'       => [
			'name'          => 'Регион Суда',
			'singular_name' => 'Регион Суда',
			'menu_name'     => 'Регион суда',
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
		'show_in_rest'      => null,
		// добавить в REST API
		'rest_base'         => null,
		// $taxonomy
		// '_builtin'              => false,
		//'update_count_callback' => '_update_post_term_count',
	] );
	register_taxonomy( 'justice_kinds', [ 'court_decisions' ], [
		'label'        => '',
		// определяется параметром $labels->name
		'labels'       => [
			'name'          => 'Вид судебного дела',
			'singular_name' => 'Вид судебного дела',
			'menu_name'     => 'Вид судебного дела',
		],
		'description'  => '',
		// описание таксономии
		'public'       => true,
		'hierarchical' => false,

		'rewrite'           => true,
		'capabilities'      => array(),
		'meta_box_cb'       => null,
		// html метабокса. callback: `post_categories_meta_box` или `post_tags_meta_box`. false — метабокс отключен.
		'show_admin_column' => false,
		// авто-создание колонки таксы в таблице ассоциированного типа записи. (с версии 3.5)
		'show_in_rest'      => null,
		// добавить в REST API
		'rest_base'         => null,
		// $taxonomy
		// '_builtin'              => false,
		//'update_count_callback' => '_update_post_term_count',
	] );
}

?>