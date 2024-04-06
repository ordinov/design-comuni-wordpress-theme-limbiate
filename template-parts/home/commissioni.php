<?php
global $autore;

$link_strutture_commissioni = dci_get_option("link_strutture_commissioni", "la_scuola");
if(is_array($link_strutture_commissioni) && ($link_strutture_commissioni > 0)) {
	$arr_commissioni = array();
	foreach ($link_strutture_commissioni as $idcommissione){
		$commissione = get_post($idcommissione);
		$arr_commissioni[$commissione->post_title] = $commissione;
	}
	?>
	<section class="section section-padding pb-0 bg-gray-light">
		<div class="container">
			<div class="row variable-gutters pt-4">
				<div class="col">
					<div class="title-section text-center mb-5">
						<h3 class="h4"><?php  _e("Commissioni e gruppi di lavoro", "design_comuni_italia");  ?></h3>
					</div><!-- /title-section -->
					<div class="responsive-tabs-wrapper">
						<div class="responsive-tabs responsive-tabs-aside responsive-tabs-aside-large">
							<ul class="pt-0">
								<?php foreach ($arr_commissioni as $commissione){ ?>
									<li><a href="#<?php echo $commissione->post_name; ?>"><?php echo $commissione->post_title; ?></a></li>
								<?php } ?>
							</ul>
							<?php foreach ($arr_commissioni as $commissione) {
								$descrizione = dci_get_meta("descrizione", "_dci_struttura_", $commissione->ID);
								$responsabile = dci_get_meta("responsabile", "_dci_struttura_", $commissione->ID);
								$persone = dci_get_meta("persone", "_dci_struttura_", $commissione->ID);
								$telefono = dci_get_meta("telefono", "_dci_struttura_", $commissione->ID);
								$mail = dci_get_meta("mail", "_dci_struttura_", $commissione->ID);
								$pec = dci_get_meta("pec", "_dci_struttura_", $commissione->ID);

								?>
								<div id="<?php echo $commissione->post_name; ?>" class="responsive-tabs-content">
									<div class="responsive-tabs-container">
										<p class="text-description mb-4"><?php echo $descrizione; ?></p>
										<?php if(is_array($responsabile) && count($responsabile)>0) { ?>
											<h6><?php _e( "Responsabile", "design_comuni_italia" ); ?></h6>
											<div class="card-deck card-deck-spaced mb-2">
												<?php
												foreach ( $responsabile as $idutente ) {
													$autore = get_user_by( "ID", $idutente );
													?>
													<div class="card card-bg card-avatar rounded">
														<a href="<?php echo get_author_posts_url( $idutente ); ?>">
															<div class="card-body">
																<?php get_template_part( "template-parts/autore/card" ); ?>
															</div>
														</a>
													</div><!-- /card card-bg card-avatar rounded -->
													<?php
												}
												?>
											</div><!-- /card-deck -->
											<?php } ?>
										<?php if(is_array($persone) && count($persone)>0){ ?>
											<h6><?php _e("Persone", "design_comuni_italia"); ?></h6>
											<div class="card-deck card-deck-spaced mb-2">
												<?php
												foreach ($persone as $idutente) {
													$autore = get_user_by("ID", $idutente);
													?>
													<div class="card card-bg card-avatar rounded">
														<a href="<?php echo get_author_posts_url($idutente); ?>">
															<div class="card-body">
																<?php get_template_part("template-parts/autore/card"); ?>
															</div>
														</a>
													</div><!-- /card card-bg card-avatar rounded -->
													<?php
												}
												?>
											</div><!-- /card-deck -->
										<?php }
										if($telefono || $mail || $pec){
											?>
											<h4 id="art-par-more"><?php _e("Per informazioni", "design_comuni_italia"); ?></h4>
													<ul class="location-list mt-3">
														<?php if($telefono){ ?><li><div class="location-title"><span><?php _e("Telefono", "design_comuni_italia"); ?>:</span></div><div class="location-content"><p><?php echo $telefono; ?></p></div></li><?php } ?>
														<?php if($mail){ ?><li><div class="location-title"><span><?php _e("Email", "design_comuni_italia"); ?>:</span></div><div class="location-content"><p><?php echo $mail; ?></p></div></li><?php } ?>
														<?php if($pec){ ?><li><div class="location-title"><span><?php _e("PEC", "design_comuni_italia"); ?>:</span></div><div class="location-content"><p><?php echo $pec; ?></p></div></li><?php } ?>
													</ul>

										<?php } ?>
                                        <a class="btn btn-redbrown mt-4" href="<?php echo get_permalink($commissione); ?>"><?php _e("Vai alla scheda", "design_comuni_italia"); ?></a>
									</div><!-- /responsive-tabs-container -->
								</div>
								<?php
							}
							?>
						</div><!-- /responsive-tabs -->
					</div><!-- /responsive-tabs-wrapper -->
				</div><!-- /col -->
			</div><!-- /row -->
		</div><!-- /container -->
	</section><!-- /section -->

	<section class="section bg-redbrown py-5">
		<div class="container">
			<div class="row variable-gutters">
				<div class="col-lg-12">
					<a class="btn btn-block btn-white rounded mb-3 mb-lg-0" href="<?php echo get_post_type_archive_link("struttura"); ?>"><?php _e("Tutta l’organizzazione", "design_comuni_italia"); ?></a>
				</div><!-- /col-lg-4 -->
			</div><!-- /row -->
		</div><!-- /container -->
	</section><!-- /section -->
	<?php
}