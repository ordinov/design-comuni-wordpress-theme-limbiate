<?php

/**
 * Generic Content template file
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Design_Comuni_Italia
 */


get_header();
?>
<main>
    <?php
    while (have_posts()) :
        the_post();
        $user_can_view_post = dci_members_can_user_view_post(get_current_user_id(), $post->ID);
        $descrizione_breve = dci_get_meta("descrizione_breve");
        $prefix = '_dci_post_';
        $allegati = dci_get_meta("allegati", $prefix, $post->ID);
    ?>
        <div class="container px-4 my-4" id="main-container">
            <div class="row">
                <div class="col px-lg-4">
                    <?php get_template_part("template-parts/common/breadcrumb"); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 px-lg-4 py-lg-2">
                    <h1 data-audio><?php the_title(); ?></h1>
                    <p data-audio>
                        <?php echo $descrizione_breve; ?>
                    </p>
                </div>
                <div class="col-lg-3 offset-lg-1">
                    <?php
                    $inline = true;
                    get_template_part('template-parts/single/actions');
                    ?>
                </div>
            </div>
        </div>

        <div class="container ">
            <article class="article-wrapper" data-audio>

                <div class="row">
                    <div class="col-lg-12">
                        <?php
                        the_content();
                        ?>
                    </div>
                </div>

                <?php if (is_array($allegati) && count($allegati)) { ?>
                    <article class="it-page-section anchor-offset mt-5">
                        <h4 id="allegati">Allegati</h4>
                        <div class="card-wrapper card-teaser-wrapper card-teaser-wrapper-equal">
                            <?php foreach ($allegati as $all_url) {
                                $all_id = attachment_url_to_postid($all_url);
                                $allegato = get_post($all_id);
                            ?>
                                <div class="card card-teaser shadow-sm p-4 mt-3 rounded border border-light flex-nowrap">
                                    <svg class="icon" aria-hidden="true">
                                        <use xlink:href="#it-clip"></use>
                                    </svg>
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <a class="text-decoration-none" href="<?php echo get_the_guid($allegato); ?>" aria-label="Scarica l'allegato <?php echo $allegato->post_title; ?>" title="Scarica l'allegato <?php echo $allegato->post_title; ?>">
                                                <?php echo $allegato->post_title; ?>
                                            </a>
                                        </h5>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </article>
                    <div id="allegati-divider"></div>
                <?php } ?>
                
                <div class="row">
                    <div class="col-lg-12">
                        <?php get_template_part("template-parts/single/bottom"); ?>
                    </div><!-- /col-lg-9 -->
                </div><!-- /row -->

            </article>
        </div>
        <?php get_template_part("template-parts/common/valuta-servizio"); ?>

    <?php
    endwhile; // End of the loop.
    ?>
</main>
<?php get_footer(); ?>