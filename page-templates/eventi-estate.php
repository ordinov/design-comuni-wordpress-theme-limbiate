<?php
/* Template Name: Eventi Estate (sito Notizie)
 *
 * Eventi template file
 *
 * @package Design_Comuni_Italia
 */
global $post;
get_header();

?>
<main>
	<?php
	while (have_posts()) :
		the_post();

		$img = dci_get_option('immagine', 'eventi');
		$didascalia = dci_get_option('didascalia', 'eventi');
		$calendar = dci_get_option('calendar', 'eventi') === 'on';
		$valuta_servizio = dci_get_option('valuta_servizio', 'eventi') === 'on';
		$assistenza_contatti = dci_get_option('assistenza_contatti', 'eventi') === 'on';
	?> <?php get_template_part("template-parts/hero/hero"); ?>
		<?php if ($img) { ?>
			<section class="hero-img mb-20 mb-lg-50">
				<section class="it-hero-wrapper it-hero-small-size cmp-hero-img-small">
					<div class="img-responsive-wrapper">
						<div class="img-responsive">
							<div class="img-wrapper">
								<?php dci_get_img($img); ?>
							</div>
						</div>
					</div>
				</section>
			</section>
		<?php } ?>

		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<?php the_content(); ?>
				</div>
			</div>
		</div>

		<?php
		if ($calendar) {
         // FILTER TAXONOMY ID, THIS IS HOW EVENTI-ESTATE DIFFERS FROM EVENTI
			get_template_part("template-parts/eventi/calendar", null, ['filterTaxonomyId' => 473]);
		} else {
			get_template_part("template-parts/vivere-comune/eventi");
		}
		?>
		<?php // get_template_part("template-parts/vivere-comune/luoghi"); 
		?>

		<?php if ($valuta_servizio) get_template_part("template-parts/common/valuta-servizio"); ?>
		<?php if ($assistenza_contatti) get_template_part("template-parts/common/assistenza-contatti"); ?>

	<?php
	endwhile; // End of the loop.
	?>
</main>

<?php
get_footer();
