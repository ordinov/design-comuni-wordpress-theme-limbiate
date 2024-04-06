<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Design_Comuni_Italia
 */
?>
<?php get_template_part("template-parts/common/search-modal"); ?>

<footer class="it-footer" id="footer">
    <div class="it-footer-main">

        <div class="container">
            <div class="row">
                <div class="col-12 footer-items-wrapper logo-wrapper">
                    <img class="ue-logo" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/logo-eu-inverted.svg" alt="logo Unione Europea">
                    <div class="it-brand-wrapper">
                        <a href="<?php echo home_url() ?>">
                            <?php get_template_part("template-parts/common/logo"); ?>
                            <div class="it-brand-text">
                                <h2 class="no_toc"><?php echo dci_get_option("nome_comune"); ?></h2>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div id="footer-widget-area" class="row">
                <div class="hr"><hr></div>
                <?php if (is_active_sidebar('limbiate-footer-area-1')) { ?>
                    <div class="col-md-4 footer-items-wrapper">
                        <?php dynamic_sidebar('limbiate-footer-area-1'); ?>
                    </div>
                <?php } ?>
                <?php if (is_active_sidebar('limbiate-footer-area-2')) { ?>
                    <div class="col-md-4 footer-items-wrapper">
                        <?php dynamic_sidebar('limbiate-footer-area-2'); ?>
                    </div>
                <?php } ?>
                <?php if (is_active_sidebar('limbiate-footer-area-3')) { ?>
                    <div class="col-md-4 footer-items-wrapper">
                        <?php dynamic_sidebar('limbiate-footer-area-3'); ?>
                    </div>
                <?php } ?>
            </div>
            <hr>

            <?php if (dci_get_option("media_policy", 'footer') || dci_get_option("sitemap", 'footer')) { ?>
                <div class="row">
                    <div class="col-12 footer-items-wrapper">
                        <div class="footer-bottom">
                            <?php if (dci_get_option("media_policy", 'footer')) { ?>
                                <a href="<?php echo dci_get_option("media_policy", 'footer'); ?>">Media policy</a>
                            <?php } ?>
                            <?php if (dci_get_option("sitemap", 'footer')) { ?>
                                <a href="<?php echo dci_get_option("sitemap", 'footer'); ?>">Mappa del sito</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

</body>

</html>