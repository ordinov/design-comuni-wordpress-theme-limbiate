<?php
/**
 * The template for displaying author
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#author
 *
 * @package Design_Comuni_Italia
 */

get_header();


$authordata = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));

$author_id = $authordata->ID;
$bio = get_the_author_meta( 'description');
//$nome = get_the_author_meta('first_name');
//$cognome = get_the_author_meta('last_name');


//$foto_url = get_the_author_meta('_dci_persona_foto');
//$foto_id = attachment_url_to_postid($foto_url);
//$image = wp_get_attachment_image($foto_id, "item-thumb");
$image_url = dci_get_user_avatar($authordata);

$ruolo_scuola = get_the_author_meta('_dci_persona_ruolo_scuola');
$ruolo_docente = get_the_author_meta('_dci_persona_ruolo_docente');
$ruolo_non_docente = get_the_author_meta('_dci_persona_ruolo_non_docente');

$tipo_posto = get_the_author_meta('_dci_persona_tipo_posto');
//$tipo_supplenza = get_the_author_meta('_dci_persona_tipo_supplenza');
//$durata_supplenza = get_the_author_meta('_dci_persona_durata_supplenza');

$incarico_docente = get_the_author_meta('_dci_persona_incarico_docente');
$durata_incarico_docente = get_the_author_meta('_dci_persona_durata_incarico_docente');

$altre_info = get_the_author_meta('_dci_persona_altre_info');
$email_pubblico = get_the_author_meta('_dci_persona_email_pubblico');
$telefono_pubblico = get_the_author_meta('_dci_persona_telefono_pubblico');

$str_ruolo = "";
if($ruolo_scuola == "dirigente"){
    $str_ruolo .= "Dirigente Scolastico ";
}else if($ruolo_scuola == "docente"){
    $str_ruolo .= "Docente ";

    if($tipo_posto == "sostegno"){
        /*
        if($tipo_supplenza == "annuale")
            $str_ruolo .= "annuale di sostegno ";
        else if($tipo_supplenza == "termine")
            $str_ruolo .= "<strong>di sostegno</strong> fino a termine ";
        else if($tipo_supplenza == "data"){
            $str_ruolo .= "<strong>di sostegno</strong> " ;
            if($durata_supplenza != "")
                $str_ruolo .= "fino al  ".$durata_supplenza." ";

        }
        else*/
        $str_ruolo .= "di sostegno ";
    }

    if($ruolo_docente == "infanzia"){
        $str_ruolo .= "della <strong>scuola dell'infanzia</strong>";
    }else if($ruolo_docente == "primaria"){
        $str_ruolo .= "di <strong>scuola primaria</strong>";
    }else if($ruolo_docente == "secondaria1"){
        $str_ruolo .= "di <strong>scuola secondaria I grado</strong>";
    }else if($ruolo_docente == "secondaria2"){
        $str_ruolo .= "di <strong>scuola secondaria II grado</strong>";
    }else if($ruolo_docente == "formazione"){
        $str_ruolo .= "dei <strong>percorsi di istruzione e formazione professionale</strong>";
    }

    if($incarico_docente == "determinato"){
        $str_ruolo .= " con incarico a tempo determinato ";

        if($durata_incarico_docente != ""){
            $str_ruolo .= " <small>(termine incarico: ".$durata_incarico_docente.")</small>";
        }

    }else if($incarico_docente == "indeterminato"){
        $str_ruolo .= " con incarico a tempo indeterminato ";
    }




}else if($ruolo_scuola == "personaleata"){

    if($ruolo_non_docente == "direttore-amministrativo"){
        $str_ruolo .= "Direttore amministrativo ";
    }else if($ruolo_non_docente == "tecnico"){
        $str_ruolo .= "Personale tecnico ";
    }else if($ruolo_non_docente == "amministrativo"){
        $str_ruolo .= "Personale amministrativo ";
    }else if($ruolo_non_docente == "collaboratore"){
        $str_ruolo .= "Collaboratore scolastico";
    }else{
        $str_ruolo .= "Non docente ";
    }


}


// recupero le schede didattiche e di progetto e i documenti che hanno questo utente come autore

$args = array(
    'author' =>  $author_id,
    'posts_per_page' => -1,
    'post_type' => 'scheda_didattica'
);
$schede_didattiche = get_posts($args);

$args = array(
    'author' =>  $author_id,
    'posts_per_page' => -1,
    'post_type' => 'scheda_progetto'
);
$schede_progetto = get_posts($args);

$args = array(
    'author' =>  $author_id,
    'posts_per_page' => -1,
    'post_type' => 'documento'
);
$documenti = get_posts($args);

$args = array(
    'author' =>  $author_id,
    'posts_per_page' => 6,
    'post_type' => array('post'),
);
$posts = get_posts($args);

?>
    <main id="main-container" class="main-container petrol">
        <?php get_template_part("template-parts/common/breadcrumb"); ?>
        <section class="section bg-petrol py-3 py-lg-3 py-xl-5">
            <div class="container">
                <div class="row variable-gutters">
                    <div class="col-12 col-sm-3 col-lg-3 d-none d-sm-block">
                        <div class="section-thumb thumb-large mx-3">
                            <?php if($image_url) {
                                echo "<img src='".$image_url."' />";
                            } ?>
                        </div><!-- /section-thumb -->
                    </div><!-- /col-lg-2 -->
                    <div class="col-12 col-sm-9 col-md-8 col-lg-8 offset-lg-1 d-flex align-items-center">
                        <div class="section-title">
                            <h2 class="mb-3"><?php echo dci_get_display_name($author_id); ?></h2>
                            <p><?php echo $str_ruolo; ?></p>
                        </div><!-- /title-section -->
                    </div><!-- /col-lg-5 col-md-8 -->
                </div><!-- /row -->
            </div><!-- /container -->
        </section><!-- /section -->

        <section class="section bg-white">
            <div class="container container-border-top">
                <div class="row variable-gutters">
                    <div class="col-lg-3 col-md-4 aside-border px-0">
                        <aside class="aside-main aside-sticky">
                            <div class="aside-title">
                                <a class="toggle-link-list" data-toggle="collapse" href="#lista-paragrafi" role="button" aria-expanded="true" aria-controls="lista-paragrafi">
                                    <span><?php _e("Dettagli della persona", "design_comuni_italia"); ?></span>
                                    <svg class="icon icon-toggle svg-arrow-down-small"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-arrow-down-small"></use></svg>
                                </a>
                            </div>
                            <div id="lista-paragrafi" class="link-list-wrapper collapse show">
                                <ul class="link-list">
                                    <?php if($bio != "") { ?>
                                        <li>
                                            <a class="list-item scroll-anchor-offset" href="#art-par-bio" title="Vai al paragrafo <?php _e("Biografia", "design_comuni_italia"); ?>"><?php _e("Biografia", "design_comuni_italia"); ?></a>
                                        </li>
                                    <?php } ?>
                                    <?php if(count($schede_didattiche) > 0)  { ?>
                                        <li>
                                            <a class="list-item scroll-anchor-offset" href="#art-par-didattica" title="Vai al paragrafo <?php _e("Schede didattiche", "design_comuni_italia"); ?>"><?php _e("Schede didattiche", "design_comuni_italia"); ?></a>
                                        </li>
                                    <?php } ?>
                                    <?php if(count($schede_progetto) > 0)  { ?>
                                        <li>
                                            <a class="list-item scroll-anchor-offset" href="#art-par-progetti" title="Vai al paragrafo <?php _e("Progetti", "design_comuni_italia"); ?>"><?php _e("Progetti", "design_comuni_italia"); ?></a>
                                        </li>
                                    <?php } ?>
                                    <?php if(count($documenti) > 0)  { ?>
                                        <li>
                                            <a class="list-item scroll-anchor-offset" href="#art-par-documenti" title="Vai al paragrafo <?php _e("Documenti", "design_comuni_italia"); ?>"><?php _e("Documenti", "design_comuni_italia"); ?></a>
                                        </li>
                                    <?php } ?>
                                    <?php if(count($posts) > 0)  { ?>
                                        <li>
                                            <a class="list-item scroll-anchor-offset" href="#art-par-articoli" title="Vai al paragrafo <?php _e("Articoli", "design_comuni_italia"); ?>"><?php _e("Articoli", "design_comuni_italia"); ?></a>
                                        </li>
                                    <?php } ?>

                                    <?php
                                    if(trim($altre_info) != ""){
                                        ?>
                                        <li>
                                            <a class="list-item scroll-anchor-offset" href="#art-par-altre-info"
                                               title="Vai al paragrafo <?php _e("Ulteriori informazioni", "design_comuni_italia"); ?>"><?php _e("Ulteriori informazioni", "design_comuni_italia"); ?></a>
                                        </li>
                                        <?php
                                    }
                                    if(($telefono_pubblico != "") || ($email_pubblico != "")){ ?>
                                        <li>
                                            <a class="list-item scroll-anchor-offset" href="#art-par-contatti" title="Vai al paragrafo <?php _e("Contatti", "design_comuni_italia"); ?>"><?php _e("Contatti", "design_comuni_italia"); ?></a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </aside>

                    </div>
                    <div class="col-lg-8 col-md-8 offset-lg-1 pt84">
                        <article class="article-wrapper">
                            <?php if($bio != "") { ?>
                                <h4 id="art-par-bio"><?php _e("Biografia", "design_comuni_italia"); ?></h4>
                                <div class="row variable-gutters">
                                    <div class="col-lg-9">
                                        <p><?php echo $bio; ?></p>
                                    </div><!-- /col-lg-9 -->
                                </div><!-- /row -->
                            <?php }
                            if (count($schede_didattiche) > 0) {
                                ?>
                                <h4 id="art-par-didattica"
                                    class="mb-4"><?php _e("Schede didattiche", "design_comuni_italia"); ?></h4>
                                <div class="row variable-gutters">
                                    <div class="col-lg-12">
                                        <div class="card-deck card-deck-spaced">
                                            <?php foreach ($schede_didattiche as $scheda) { ?>
                                                <div class="card card-bg card-icon rounded">
                                                    <div class="card-body">
                                                        <svg class="icon svg-project">
                                                            <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                 xlink:href="#svg-project"></use>
                                                        </svg>
                                                        <div class="card-icon-content">
                                                            <p>
                                                                <strong><a href="<?php echo get_permalink($scheda); ?>"> <?php echo $scheda->post_title; ?></a></strong>
                                                            </p>
                                                            <small><?php echo dci_get_meta("descrizione", "_dci_scheda_didattica_", $scheda->ID); ?></small>
                                                        </div><!-- /card-icon-content -->
                                                    </div><!-- /card-body -->
                                                </div><!-- /card card-bg card-icon rounded -->
                                            <?php } ?>
                                        </div><!-- /card-deck card-deck-spaced -->
                                    </div><!-- /col-lg-12 -->
                                </div><!-- /row -->
                            <?php } ?>
                            <?php
                            if (count($schede_progetto) > 0) {
                                ?>
                                <h4 id="art-par-progetti"  class="mb-4"><?php _e("Progetti", "design_comuni_italia"); ?></h4>
                                <div class="row variable-gutters mb-4">
                                    <div class="col-lg-12">
                                        <div class="card-deck card-deck-spaced">
                                            <?php foreach ($schede_progetto as $scheda) { ?>
                                                <div class="card card-bg card-icon rounded">
                                                    <div class="card-body">
                                                        <svg class="icon svg-project">
                                                            <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                 xlink:href="#svg-project"></use>
                                                        </svg>
                                                        <div class="card-icon-content">
                                                            <p>
                                                                <strong><a href="<?php echo get_permalink($scheda); ?>"><?php echo $scheda->post_title; ?></a></strong>
                                                            </p>
                                                            <small><?php echo dci_get_meta("descrizione", "_dci_scheda_progetto_", $scheda->ID); ?></small>
                                                        </div><!-- /card-icon-content -->
                                                    </div><!-- /card-body -->
                                                </div><!-- /card card-bg card-icon rounded -->
                                            <?php } ?>
                                        </div><!-- /card-deck card-deck-spaced -->
                                    </div><!-- /col-lg-12 -->
                                </div><!-- /row -->
                            <?php }

                            if (count($documenti) > 0) {
                                ?>
                                <h4 id="art-par-documenti"  class="mb-4"><?php _e("Documenti", "design_comuni_italia"); ?></h4>
                                <div class="row variable-gutters mb-4">
                                    <div class="col-lg-12">
                                        <div class="card-deck card-deck-spaced">
                                            <?php foreach ($documenti as $doc) { ?>
                                                <div class="card card-bg card-icon rounded">
                                                    <div class="card-body">
                                                        <svg class="icon it-pdf-document">
                                                            <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                 xlink:href="#it-pdf-document"></use>
                                                        </svg>
                                                        <div class="card-icon-content">
                                                            <p>
                                                                <strong><a href="<?php echo get_permalink($doc); ?>"><?php echo $doc->post_title; ?></a></strong>
                                                            </p>
                                                        </div><!-- /card-icon-content -->
                                                    </div><!-- /card-body -->
                                                </div><!-- /card card-bg card-icon rounded -->
                                            <?php } ?>
                                        </div><!-- /card-deck card-deck-spaced -->
                                    </div><!-- /col-lg-12 -->
                                </div><!-- /row -->
                            <?php }

                            if(trim($altre_info) != ""){
                                ?>
                                <h4 id="art-par-altre-info"
                                    class="mb-4"><?php _e("Ulteriori informazioni", "design_comuni_italia"); ?></h4>
                                <div class="row variable-gutters">
                                    <div class="col-lg-9">
                                        <p><?php echo $altre_info; ?></p>
                                    </div><!-- /col-lg-9 -->
                                </div><!-- /row -->
                                <?php
                            }
                            ?>

                            <?php if(($telefono_pubblico != "") || ($email_pubblico != "")){ ?>
                                <h4 id="art-par-contatti"><?php _e("Contatti", "design_comuni_italia"); ?></h4>
                                <div class="row variable-gutters">
                                    <div class="col-lg-9">
                                        <ul>
                                            <?php if($telefono_pubblico != ""){?><li><strong><?php _e("Telefono", "design_comuni_italia"); ?>:</strong> <?php echo $telefono_pubblico; ?></li><?php } ?>
                                            <?php if($email_pubblico != ""){?><li><strong><?php _e("Email", "design_comuni_italia"); ?>:</strong> <a href="mailto:<?php echo $email_pubblico; ?>"><?php echo $email_pubblico; ?></a></li><?php } ?>
                                        </ul>
                                    </div><!-- /col-lg-9 -->
                                </div><!-- /row -->
                            <?php } ?>
                        </article>
                    </div><!-- /col-lg-8 -->
                </div><!-- /row -->
            </div><!-- /container -->
        </section>
        <?php
        // controllo se esistono post e eventi pubblicati dall'autore
        if(count($posts)) {
            ?>
            <section class="section bg-gray-gradient py-5">
                <div class="container pt-3">

                    <div class="row variable-gutters">
                        <div class="col-lg-12">
                            <h3 class="mb-5 text-center semi-bold text-gray-primary" id="art-par-articoli"><?php _e( "Articoli pubblicati da ", "design_comuni_italia" ); ?><?php echo dci_get_display_name($author_id); ?></h3>
                            <div class="owl-carousel carousel-theme carousel-large">
                                <?php
                                foreach ( $posts as $post ) {

                                    ?>
                                    <div class="item">
                                        <div class="card card-bg bg-white card-thumb-rounded">
                                            <div class="card-body">
                                                <div class="badges mb-2">
                                                    <?php
                                                    $argomenti = dci_get_argomenti_of_post($post);
                                                    if(count($argomenti)) {
                                                        foreach ( $argomenti as $argomento ) { ?>
                                                            <a href="<?php echo get_term_link($argomento); ?>"  class="badge badge-sm badge-pill badge-outline-greendark" title="<?php _e("Vai all'argomento", "design_comuni_italia"); ?>: <?php echo $argomento->name; ?>"><?php echo $argomento->name; ?></a><?php
                                                        }
                                                    }
                                                    ?>
                                                </div><!-- /badges -->
                                                <h4 class="h5"><a href="<?php echo get_permalink($post); ?>"><strong><?php echo get_the_title($post); ?></strong></a></h4>
                                                <p><?php echo get_the_excerpt($post); ?></p>
                                            </div><!-- /card-body -->
                                        </div><!-- /card -->
                                    </div><!-- /item -->
                                    <?php
                                }
                                ?>
                            </div><!-- /carousel-large -->
                        </div><!-- /col-lg-12 -->
                    </div><!-- /row -->
                </div><!-- /container -->
            </section>
            <?php
        }
        ?>
    </main><!-- #main -->
<?php
get_footer();
