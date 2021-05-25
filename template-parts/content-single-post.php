<article class="single-post mt-2 mb-5 mt-sm-5">
    <h1 class="single-post-title text-center"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
	<?php the_content(); ?>
	<?php if ( get_the_tag_list() ) : ?>
        <div class="labels-list">
            <b>Метки:</b> <?= get_the_tag_list(); ?>
        </div>
	<?php endif; ?>
	<?php $taxonomies = get_the_taxonomies();
	unset( $taxonomies['category'] );
	unset( $taxonomies['post_tag'] );
	?>
	<?php if ( is_array( $taxonomies ) && count( $taxonomies ) ) : ?>
        <b>Категории:</b>
        <div class="cats-list">
			<?php foreach ( $taxonomies as $taxonomy ) : ?>
				<?= $taxonomy ?> <br>
                <!--                <b>Категории:</b> --><? //= get_the_category_list( '', 'multiple' ); ?>
			<?php endforeach; ?>
        </div>
	<?php endif; ?>
    <div class="row no-gutters justify-content-between align-items-center">
        <div class="share-btn btn btn-blue">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                <path d="M564.907 196.35L388.91 12.366C364.216-13.45 320 3.746 320 40.016v88.154C154.548 130.155 0 160.103 0 331.19c0 94.98 55.84 150.231 89.13 174.571 24.233 17.722 58.021-4.992 49.68-34.51C100.937 336.887 165.575 321.972 320 320.16V408c0 36.239 44.19 53.494 68.91 27.65l175.998-184c14.79-15.47 14.79-39.83-.001-55.3zm-23.127 33.18l-176 184c-4.933 5.16-13.78 1.73-13.78-5.53V288c-171.396 0-295.313 9.707-243.98 191.7C72 453.36 32 405.59 32 331.19 32 171.18 194.886 160 352 160V40c0-7.262 8.851-10.69 13.78-5.53l176 184a7.978 7.978 0 0 1 0 11.06z"/>
            </svg>
            <span><?php esc_html_e( 'Поделиться', 'v-art' ); ?></span>
        </div>
        <div class="d-none">
			<?php echo do_shortcode( '[addtoany]' ); ?>
        </div>
		<?php echo do_shortcode( '[wp_ulike]' ); ?>
    </div>
	<?= referendum_get_author_block(); ?>
</article>