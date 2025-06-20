<?php

/**
 * Template for displaying single "Lavori Pubblici" posts
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

        // Meta data
        $gallery_images = get_post_meta(get_the_ID(), 'gallery_images', true);
        $importo_appalto = get_post_meta(get_the_ID(), 'importo_appalto', true);
        $origine_finanziamento = get_post_meta(get_the_ID(), 'origine_finanziamento', true);
        $indirizzo_esatto = get_post_meta(get_the_ID(), 'indirizzo_esatto', true);
        $quartiere = get_post_meta(get_the_ID(), 'quartiere', true);
        if (!empty($quartiere)) {
            $quartiere_map = [
                'centro' => 'Quartiere Centro',
                'ceresolo' => 'Quartiere Ceresolo',
                'fiori' => 'Villaggio dei Fiori',
                'giovi' => 'Villaggio dei Giovi',
                'sanfrancesco' => 'Quartiere San Francesco',
                'sole' => 'Villaggio del Sole',
            ];
            if (array_key_exists($quartiere, $quartiere_map)) {
                $quartiere = $quartiere_map[$quartiere];
            }
        }
        $data_inizio_lavori = get_post_meta(get_the_ID(), 'data_inizio_lavori', true);
        $breve_descrizione = get_post_meta(get_the_ID(), 'breve_descrizione', true);
        $tipologia_lavori = get_post_meta(get_the_ID(), 'tipologia_lavori', true);
        $html_content = get_the_content(get_the_ID());
        $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
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
                        <?php echo wp_kses_post($breve_descrizione); ?>
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

        <div class="container">
            <article class="article-wrapper" data-audio>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="lavori-pubblici-container">
                            <div class="gallery-grid" id="masonry-grid">
                                <?php
                                if ($featured_image) {
                                    echo '<div class="gallery-item">';
                                    echo '<img src="' . esc_url($featured_image) . '">';
                                    echo '</div>';
                                }
                                if ($gallery_images) {
                                    $image_ids = explode(',', $gallery_images);
                                    foreach ($image_ids as $image_id) {
                                        $image_url = wp_get_attachment_image_src($image_id, 'large');
                                        echo '<div class="gallery-item">';
                                        echo '<img src="' . esc_url($image_url[0]) . '" alt="' . esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', true)) . '">';
                                        echo '</div>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="content-column">
                            <h4><?php _e('Dettagli:', 'textdomain'); ?></h4>
                            <hr>
                            <?php if (!empty($data_inizio_lavori)) { ?>
                                <p><strong><?php _e('Anno di realizzazione:', 'textdomain'); ?></strong> <?php echo esc_html($data_inizio_lavori); ?></p>
                            <?php } ?>
                            <?php if (!empty($indirizzo_esatto)) { ?>
                                <p><strong><?php _e('Location:', 'textdomain'); ?></strong> <?php echo esc_html($indirizzo_esatto); ?></p>
                            <?php } ?>
                            <?php if (!empty($tipologia_lavori)) { ?>
                                <p><strong><?php _e('Tipologia Lavori:', 'textdomain'); ?></strong> <?php echo esc_html($tipologia_lavori); ?></p>
                            <?php } ?>
                            <?php if (!empty($importo_appalto)) { ?>
                                <p><strong><?php _e('Importo Appalto:', 'textdomain'); ?></strong> <?php echo esc_html($importo_appalto); ?></p>
                            <?php } ?>
                            <?php if (!empty($origine_finanziamento)) { ?>
                                <p><strong><?php _e('Origine del Finanziamento:', 'textdomain'); ?></strong> <?php echo esc_html($origine_finanziamento); ?></p>
                            <?php } ?>
                            <?php if (!empty($quartiere)) { ?>
                                <p><strong><?php _e('Quartiere:', 'textdomain'); ?></strong> <?php echo esc_html($quartiere); ?></p>
                            <?php } ?>
                            <hr>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-12">
                        <?php
                        the_content();
                        ?>
                    </div>
                </div>

                <?php
                // Display attachments if available
                $prefix = '_dci_post_';
                $allegati = dci_get_meta("allegati", $prefix, $post->ID);
                if (is_array($allegati) && count($allegati)) { ?>
                    <article class="it-page-section anchor-offset mt-5">
                        <h4 id="allegati"><?php _e('Allegati', 'textdomain'); ?></h4>
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
                                            <a class="text-decoration-none" href="<?php echo get_the_guid($allegato); ?>" aria-label="<?php printf(__('Scarica l\'allegato %s', 'textdomain'), $allegato->post_title); ?>" title="<?php printf(__('Scarica l\'allegato %s', 'textdomain'), $allegato->post_title); ?>">
                                                <?php echo esc_html($allegato->post_title); ?>
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
                    </div>
                </div>

            </article>
        </div>
        <?php get_template_part("template-parts/common/valuta-servizio"); ?>

    <?php
    endwhile; // End of the loop.
    ?>
</main>
<?php get_footer(); ?>