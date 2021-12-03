<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package referendum
 */

get_header();
?>

    <main id="post-cont" class="site-main">
        <div class="container">
            <div class="page-header">
                <h2 class="page-title text-center"><?php single_cat_title( false, true ); ?></h2>
				<?php
				the_archive_description( '<div class="archive-description">', '</div>' );
				?>
            </div><!-- .page-header -->
            <div class="row">
                <div class="col-lg-4 col-xxl-auto">
                    <div class="home-twobtns">
                        <button id="filter-btn"
                                class="btn d-flex mx-lg-auto mb-lg-2 d-lg-none btn-orange">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M479.968 0H32.038C3.613 0-10.729 34.487 9.41 54.627L192 237.255V424a31.996 31.996 0 0 0 10.928 24.082l64 55.983c20.438 17.883 53.072 3.68 53.072-24.082V237.255L502.595 54.627C522.695 34.528 508.45 0 479.968 0zM288 224v256l-64-56V224L32 32h448L288 224z"/></svg>
                            <span>Фильтр</span>
                        </button>
                        <a <?php if ( ! get_current_user_id() )
				            echo 'onclick="registrationPopup(event);"' ?>
                                href="<?= home_url(); ?>/account/?user=<?= get_current_user_id() ?>&tab=postform"
                                class="btn btn-yellow d-lg-none">
				            <?php esc_html_e('Опубликовать информацию', 'referendum'); ?>
                        </a>
                    </div>
                    <div class="home-filter">
	                    <?php echo do_shortcode( '[searchandfilter id="372"]' ); ?>
	                    <?php if (is_active_sidebar('sidebar-1')) dynamic_sidebar('sidebar-1'); ?>
                    </div>
                </div>

                <div class="col-lg-8 col-xxl-auto">

                    <div id="posts-list">
						<?php
						global $wp_query;
						var_dump(get_term_meta(get_queried_object_id()));
						$query =& $wp_query; ?>
						<?php include "search-filter/results.php"; ?>
                    </div>
                </div>

            </div>
        </div>
    </main><!-- #main -->

<?php
get_footer();
