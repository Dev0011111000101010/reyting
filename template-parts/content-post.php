<div class="home-post mt-2 mb-3 mt-sm-5">
    <h4 class="loop-post_title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
    <div class="row loop-post-cont justify-content-between no-gutters">
		<?php if ( get_post_meta(get_the_ID(), 'yvppavpyvp_16', true) ) : ?>
			<?php $img =  str_replace('?usp=sharing', '', get_post_meta(get_the_ID(), 'yvppavpyvp_16', true))  ?>
            <div class="loop-post-img">
                <?php if(!strpos($img, 'google.com')) : ?>
                <img src="<?= $img  ?>" alt="">
                <?php else: ?>
                    <iframe src="<?= $img ?>" frameborder="0"></iframe>
                <?php endif; ?>
            </div>
		<?php endif; ?>
        <div class="loop-post-text">
			<?php the_excerpt(); ?>
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
        </div>
    </div>
    <div class="row no-gutters justify-content-between align-items-center">
        <a href="<?php the_permalink(); ?>" class="btn btn-orange">Читать далее</a>
		<?php echo do_shortcode( '[wp_ulike]' ); ?>
    </div>
</div>

<hr/>
