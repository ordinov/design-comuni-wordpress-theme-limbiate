<?php

/**
 * Archivio Tassonomia Argomento
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#custom-taxonomies
 * @link https://italia.github.io/design-comuni-pagine-statiche/sito/argomento.html
 *
 * @package Design_Comuni_Italia
 */

global $argomento, $with_border, $uo_id, $custom_class;

$argomento = get_queried_object();
$img = dci_get_term_meta('immagine', "dci_term_", $argomento->term_id);
$aree_appartenenza = dci_get_term_meta('area_appartenenza', "dci_term_", $argomento->term_id);
$assessorati_riferimento = dci_get_term_meta('assessorato_riferimento', "dci_term_", $argomento->term_id);

get_header();
?>
<main>
    <div class="it-hero-wrapper it-wrapped-container" id="main-container">
        <?php if ($img) { ?>
            <div class="img-responsive-wrapper">
                <div class="img-responsive">
                    <div class="img-wrapper">
                        <?php dci_get_img($img); ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="container">
            <div class="row">
                <div class="col-12 px-0 px-lg-2">
                    <div class="it-hero-card it-hero-bottom-overlapping rounded hero-p pb-lg-80 drop-shadow <?php echo ($img ? '' : 'mt-0'); ?>">

                        <div class="row justify-content-center">
                            <div class="col-12 col-lg-10">
                                <?php
                                $custom_class = 'mt-0';
                                get_template_part("template-parts/common/breadcrumb");
                                ?>
                            </div>
                        </div>
                        <div class="row sport-wrapper justify-content-between mt-lg-2">
                            <div class="col-12 col-lg-5 offset-lg-1">
                                <h1 class="mb-3 mb-lg-4 title-xxlarge">
                                    <?php the_archive_title('<h2 class="mb-0">', '</h2>'); ?>
                                </h1>
                                <h2 class="visually-hidden" id="news-details">Dettagli</h2>
                                <p class="u-main-black text-paragraph-regular-medium mb-60">
                                    <?php the_archive_description("<p>", "</p>"); ?>
                                </p>
                            </div>
                            <div class="col-12 col-lg-5 me-lg-5">
                                <?php get_template_part("template-parts/single/actions"); ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="section bg-gray-light">
        <div class="container">
            <div class="row variable-gutters sticky-sidebar-container">
                <div class="col-lg-12 pt84">
                    <div class="row">
                        <?php if (have_posts()) : ?>
                            <?php
                            /* Start the Loop */
                            while (have_posts()) :
                                echo '<div class="col-lg-4">';
                                the_post();
                                get_template_part('template-parts/list/article', get_post_type());
                                echo '<br/>';
                                echo '</div>';
                            endwhile;
                            ?>
                            <nav class="pagination-wrapper justify-content-center col-12">
                                <?php echo dci_bootstrap_pagination(); ?>
                            </nav>
                        <?php
                        else :

                            get_template_part('template-parts/content', 'none');

                        endif;
                        ?>
                    </div><!-- /row -->
                </div><!-- /col-lg-8 -->
            </div><!-- /row -->
        </div><!-- /container -->
    </section>
</main>
<?php
get_footer();
