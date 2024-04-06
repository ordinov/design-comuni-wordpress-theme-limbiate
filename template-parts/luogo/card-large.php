<?php
global $luogo, $struttura, $c;

// controllo se è un parent, in caso recupero i dati del genitore
if($luogo->post_parent == 0){
	$card_title = $luogo->post_title;
	$indirizzo = dci_get_meta("indirizzo", '_dci_luogo_', $luogo->ID);
	$posizione_gps = dci_get_meta("posizione_gps", '_dci_luogo_', $luogo->ID);
	$cap = dci_get_meta("cap", '_dci_luogo_', $luogo->ID);
	$mail = dci_get_meta("mail", '_dci_luogo_', $luogo->ID);
	$telefono = dci_get_meta("telefono", '_dci_luogo_', $luogo->ID);
}else{
	$parent = get_post($luogo->post_parent);
	$card_title = $parent->post_title;
	$indirizzo = dci_get_meta("indirizzo", "_dci_luogo_", $luogo->post_parent);
	$posizione_gps = dci_get_meta("posizione_gps", "_dci_luogo_", $luogo->post_parent);
	$cap = dci_get_meta("cap", "_dci_luogo_", $luogo->post_parent);
	$mail = dci_get_meta("mail", "_dci_luogo_", $luogo->post_parent);
	$telefono = dci_get_meta("telefono", "_dci_luogo_", $luogo->post_parent);
}

$orario_pubblico = dci_get_wysiwyg_field("orario_pubblico", '_dci_luogo_', $luogo->ID);

if(isset($struttura->ID) && dci_get_meta("telefono", '_dci_struttura_', $struttura->ID) != "")
	$telefono = dci_get_meta("telefono", '_dci_struttura_', $struttura->ID);

if(isset($struttura->ID) && dci_get_meta("mail", '_dci_struttura_', $struttura->ID) != "")
	$mail = dci_get_meta("mail", '_dci_struttura_', $struttura->ID);

if(isset($struttura->ID) && dci_get_meta("pec", '_dci_struttura_', $struttura->ID) != "")
	$pec = dci_get_meta("pec", '_dci_struttura_', $struttura->ID);

if(isset($struttura->ID)){
	$arr_persone = dci_get_meta("persone", '_dci_struttura_', $struttura->ID);
	$persone = "";
	if(is_array($arr_persone)){
		foreach ($arr_persone as $id_persona){
			//$utente = get_user_by("id", $id_persona);
			$persone .= dci_get_display_name($id_persona)." ";
		}
	}

}
?>
<div class="card card-bg rounded mb-5">
	<div class="card-header">
        <?php if(is_singular("luogo") && ($luogo->post_parent == 0)){ ?>
            <strong class="d-block"><?php echo $card_title; ?></strong>
        <?php }else if(is_singular("luogo") && ($luogo->post_parent > 0)){
            // sono nel luogo child, stampo il nome e il link del parent
            ?>
            <a href="<?php echo get_permalink($luogo->post_parent); ?>"><strong class="d-block"><?php echo get_the_title($luogo->post_parent); ?></strong></a>
            <?php
        } else { ?>
            <a href="<?php echo get_permalink($luogo); ?>"><strong class="d-block"><?php echo $card_title; ?></strong></a>
        <?php } ?>

		<small class="d-block"><?php echo wpautop($indirizzo); ?></small>
		<!-- <small class="d-block">Quartiere Tufello - 4° Circoscrizione</small> //-->
	</div><!-- /card-header -->
	<div class="card-body p-0">
		<div class="row variable-gutters">
			<div class="col-lg-12">
				<div class="map-wrapper map-min-height">
					<div class="map" id="map_<?php echo $c; ?>"></div>
				</div>
			</div><!-- /col-lg-12 -->
		</div><!-- /row -->
	</div><!-- /card-body -->
</div><!-- /card card-bg rounded -->


<script>
    jQuery(function() {
        var mymap = L.map('map_<?php echo $c; ?>', {
            zoomControl: false,
            scrollWheelZoom: false
        }).setView([<?php echo $posizione_gps["lat"]; ?>, <?php echo $posizione_gps["lng"]; ?>], 15);
        L.marker([<?php echo $posizione_gps["lat"]; ?>, <?php echo $posizione_gps["lng"]; ?>]).addTo(mymap);

        // add the OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '',
            maxZoom: 18,
        }).addTo(mymap);
    });
</script>
