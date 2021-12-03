<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package referendum
 */
get_header();
?>

    <main class="home-main">
        <div class="container">
            <div id="main-filter">
				<?php if ( $_GET['professional'] == 'true' ) : ?>
                    <div class="extended-search">
						<?php echo do_shortcode( '[searchandfilter id="18"]' ); ?>
                    </div>
				<?php else: ?>
                    <div class="basic-search">
						<?php echo do_shortcode( '[searchandfilter id="289"]' ); ?>
                    </div>
				<?php endif; ?>
            </div>
            <div class="main-results">
				<?php if ( $_GET['professional'] == 'true' ) : ?>
                    <div class="extended-search-results">
						<?php echo do_shortcode( '[searchandfilter id="18" show="results"]' ); ?>
                    </div>
				<?php else: ?>
                    <div class="basic-search-results">
						<?php echo do_shortcode( '[searchandfilter id="289" show="results"]' ); ?>
                    </div>
				<?php endif; ?>
            </div>
        </div>
    </main>

<?php
get_footer();
