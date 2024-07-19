<?php
/* Template Name: Eventi (Lista)
 *
 * Eventi List template file
 *
 * @package Design_Comuni_Italia
 */
global $post;
get_header();
$prefix = '_dci_evento_';

?>
<main>
	<?php
	while (have_posts()) :
		the_post();

		$img = dci_get_option('immagine', 'vivi');
		$didascalia = dci_get_option('didascalia', 'vivi');
	?>
		<?php get_template_part("template-parts/hero/hero"); ?>
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
				<p class="title-xsmall cmp-hero-img-small__description">
					<?php echo $didascalia; ?>
				</p>
			</section>
		<?php } ?>
		<?php
		$args = array(
			'post_type'      => 'evento',
			'posts_per_page' => -1,
		);
		$eventi_query = new WP_Query($args);
		$allPosts = $eventi_query->get_posts();
		usort($allPosts, function ($postA, $postB) use ($prefix) {
			// Fetch timestamps for each post
			$timestampA = dci_get_meta('data_orario_inizio', $prefix, $postA->ID);
			$timestampB = dci_get_meta('data_orario_inizio', $prefix, $postB->ID);

			// Compare timestamps
			if ($timestampA == $timestampB) {
				return 0;
			}
			return ($timestampA < $timestampB) ? -1 : 1;
		});
		?>
		<div class="bg-grey-dsk py-5">
			<div class="container">
				<div class="row g-4">
					<?php
					foreach ($allPosts as $postEvent) {
						$eventColor = '#707070';
						$arguments = wp_get_post_terms($postEvent->ID, 'argomenti', array('fields' => 'names'));
						$arguments = array_map('strtolower', $arguments);

						// SHOW ONLY SERALE, FAMILY, OFF
						if (!in_array('serale', $arguments) &&
						!in_array('family', $arguments) && 
						!in_array('off', $arguments)) continue;

						if (in_array('serale', $arguments)) $eventColor = '#33573F';
						if (in_array('family', $arguments)) $eventColor = '#ED6D11';
						if (in_array('off', $arguments)) $eventColor = '#5B232B';
						$url = get_the_permalink($postEvent);
						$prefix = '_dci_evento_';
						$img = get_the_post_thumbnail($postEvent);
						$descrizione = dci_get_meta('descrizione_breve', $prefix, $postEvent->ID);
						$timestamp = dci_get_meta('data_orario_inizio', $prefix, $postEvent->ID);
						$arrdata = explode('-', date_i18n("j-F-Y", $timestamp));
					?>
						<div class="col-lg-6 col-xl-4" onclick="window.location.href='<?php echo $url; ?>';" style="cursor:pointer" ;>
							<div class="card-wrapper shadow-sm rounded border border-light">
								<div class="card no-after rounded">
									<div class="img-responsive-wrapper">
										<div class="img-responsive img-responsive-panoramic">
											<figure class="img-wrapper">
												<?php dci_get_img($img, 'rounded-top img-fluid'); ?>
											</figure>
											<div class="card-calendar d-flex flex-column justify-content-center" style="background: <?php echo $eventColor; ?>; color: white;">
												<span class="card-date"><?php echo $arrdata[0]; ?></span>
												<span class="card-day"><?php echo $arrdata[1] . '<br>' . $arrdata[2]; ?></span>
											</div>
										</div>
									</div>
									<div class="card-body">
										<h4><?php echo $postEvent->post_title; ?></h4>
										<div class="category-top" style="text-transform:none;">
											<p><?php echo $descrizione; ?></p>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>
					<div class="d-flex justify-content-end">
						<a href="<?php echo get_permalink(get_page_by_title('Eventi')); ?>" class="btn btn-outline-primary full-mb" aria-label="Vedi tutti gli eventi" data-element="live-button-events">
							Visualizza nel calendario
							<svg class="icon icon-primary icon-xs ml-10">
								<use href="#it-arrow-right"></use>
							</svg>
						</a>
					</div>
				</div>
			</div>
		</div>
	<?php
	endwhile; // End of the loop.
	?>
</main>

<?php
get_footer();
