<?php
    get_header();
    $sidebar = is_active_sidebar( 'blog-widget' ) ? get_theme_mod( 'blog_sidebar', 'right' ) : 'full';
?>

<div class="<?php if ( in_category(array('press')) ) { ?>max-width--site gutter-padding--full col-sm--fluid<?php } else { ?>container<?php } ?>">
    <div class="row">
        <?php
            switch( $sidebar ):
                case 'left':
            /*! sidebar in left
            ------------------------------------------------->*/
        ?>
            <div class="col-md-3">
                <?php get_sidebar(); ?>
            </div>

            <main id="main" class="col-md-9 col-lg-9">
                <?php
                    if ( have_posts() ):
                        while ( have_posts() ): the_post();
				if (is_singular('post')) {
					if ( in_category(array('press')) ) {
						get_template_part( 'template-parts/content', 'press' );
					} else {
						get_template_part( 'template-parts/content', 'blog' );
					}
				} else {
					get_template_part( 'template-parts/content', get_post_format() );
				}
				
                        endwhile;

                        if ( comments_open() || get_comments_number() ):
                            comments_template();
                        endif;
                    else :
                        get_template_part( 'template-parts/content', 'none' );
                    endif;
                ?>
            </main>
        <?php
            break;
            case 'right':
            /*! sidebar in right
            ------------------------------------------------->*/
        ?>
            <main id="main" class="col-md-9 col-lg-9">
                <?php
                if ( have_posts() ):
                    while ( have_posts() ): the_post();
                 if (is_singular('post')) {
					if ( in_category(array('press')) ) {
						get_template_part( 'template-parts/content', 'press' );
					} else {
						get_template_part( 'template-parts/content', 'blog' );
					}
				} else {
					get_template_part( 'template-parts/content', get_post_format() );
				}
                    endwhile;

                    if ( comments_open() || get_comments_number() ):
                        comments_template();
                    endif;
                else :
                    get_template_part( 'template-parts/content', 'none' );
                endif; ?>
            </main>

            <div class="col-md-3">
                <?php get_sidebar(); ?>
            </div>
        <?php
            break;
            case 'full':
            /*! no sidebar
            ------------------------------------------------->*/
        ?>
            <main id="main" class="col-md-12 col-lg-12">
                <?php
                    if ( have_posts() ):
                        while ( have_posts() ): the_post();
                            if (is_singular('post')) {
					if ( in_category(array('press')) ) {
						get_template_part( 'template-parts/content', 'press' );
					} else {
						get_template_part( 'template-parts/content', 'blog' );
					}
				} else {
					get_template_part( 'template-parts/content', get_post_format() );
				}
                        endwhile;

                        if ( comments_open() || get_comments_number() ):
                            comments_template();
                        endif;
                    else :
                        get_template_part( 'template-parts/content', 'none' );
                    endif;
                ?>
            </main>
        <?php
            break;
            endswitch;
        ?>
    </div>
</div>

<?php
    get_footer();