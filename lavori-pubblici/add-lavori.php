<?php

function create_tipologia_lavori_taxonomy()
{
    $labels = array(
        'name'              => _x('Tipologie Lavori', 'taxonomy general name', 'limbiate'),
        'singular_name'     => _x('Tipologia Lavoro', 'taxonomy singular name', 'limbiate'),
        'search_items'      => __('Cerca Tipologie', 'limbiate'),
        'all_items'         => __('Tutte le Tipologie', 'limbiate'),
        'parent_item'       => __('Tipologia Genitore', 'limbiate'),
        'parent_item_colon' => __('Tipologia Genitore:', 'limbiate'),
        'edit_item'         => __('Modifica Tipologia', 'limbiate'),
        'update_item'       => __('Aggiorna Tipologia', 'limbiate'),
        'add_new_item'      => __('Aggiungi Nuova Tipologia', 'limbiate'),
        'new_item_name'     => __('Nuovo Nome Tipologia', 'limbiate'),
        'menu_name'         => __('Tipologia Lavori', 'limbiate'),
    );

    $args = array(
        'hierarchical'      => true,  // Set to false if you don't want parent-child relationships
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'tipologia-lavori'),
    );

    register_taxonomy('tipologia_lavori', array('lavori_pubblici'), $args);
}
add_action('init', 'create_tipologia_lavori_taxonomy');

function register_lavori_pubblici_post_type()
{
    $labels = array(
        'name'               => _x('Lavori Pubblici', 'post type general name', 'limbiate'),
        'singular_name'      => _x('Lavoro Pubblico', 'post type singular name', 'limbiate'),
        'menu_name'          => _x('Lavori Pubblici', 'admin menu', 'limbiate'),
        'name_admin_bar'     => _x('Lavoro Pubblico', 'add new on admin bar', 'limbiate'),
        'add_new'            => _x('Aggiungi Nuovo', 'lavoro', 'limbiate'),
        'add_new_item'       => __('Aggiungi Nuovo Lavoro Pubblico', 'limbiate'),
        'new_item'           => __('Nuovo Lavoro Pubblico', 'limbiate'),
        'edit_item'          => __('Modifica Lavoro Pubblico', 'limbiate'),
        'view_item'          => __('Visualizza Lavoro Pubblico', 'limbiate'),
        'all_items'          => __('Tutti i Lavori Pubblici', 'limbiate'),
        'search_items'       => __('Cerca Lavori Pubblici', 'limbiate'),
        'parent_item_colon'  => __('Lavoro Pubblico Genitore:', 'limbiate'),
        'not_found'          => __('Nessun Lavoro Pubblico trovato.', 'limbiate'),
        'not_found_in_trash' => __('Nessun Lavoro Pubblico trovato nel Cestino.', 'limbiate')
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => str_contains(get_site_url(), 'admin.'),
        'query_var'          => true,
        'rewrite'            => array('slug' => 'lavori-pubblici'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,  // Position 5 places it below "Posts"
        'menu_icon'          => 'dashicons-admin-tools',  // Choose an appropriate icon from the Dashicons library
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
        'taxonomies'         => array('tipologia_lavori'),  // Link to the custom taxonomy
    );

    register_post_type('lavori_pubblici', $args);
}
add_action('init', 'register_lavori_pubblici_post_type');

function set_custom_edit_lavori_pubblici_columns($columns)
{
    unset($columns['author']);
    unset($columns['date']);

    $columns['title'] = __('Nome Opera', 'limbiate');
    $columns['importo_appalto'] = __('Importo Appalto', 'limbiate');
    $columns['origine_finanziamento'] = __('Origine del Finanziamento', 'limbiate');
    $columns['quartiere'] = __('Quartiere', 'limbiate');
    $columns['date'] = __('Data', 'limbiate');

    return $columns;
}
add_filter('manage_lavori_pubblici_posts_columns', 'set_custom_edit_lavori_pubblici_columns');

function custom_lavori_pubblici_column($column, $post_id)
{
    switch ($column) {

        case 'importo_appalto':
            echo get_post_meta($post_id, 'importo_appalto', true);
            break;

        case 'origine_finanziamento':
            echo get_post_meta($post_id, 'origine_finanziamento', true);
            break;

        case 'quartiere':
            echo get_post_meta($post_id, 'quartiere', true);
            break;
    }
}
add_action('manage_lavori_pubblici_posts_custom_column', 'custom_lavori_pubblici_column', 10, 2);

function lavori_pubblici_add_meta_boxes()
{
    add_meta_box(
        'lavori_pubblici_details',
        __('Dettagli Lavoro Pubblico', 'limbiate'),
        'lavori_pubblici_details_callback',
        'lavori_pubblici',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'lavori_pubblici_add_meta_boxes');

function lavori_pubblici_details_callback($post)
{
    wp_nonce_field(basename(__FILE__), 'lavori_pubblici_nonce');

    $lavori_pubblici_stored_meta = get_post_meta($post->ID);

    // Function to get the value or empty string if not set
    function get_meta_value($meta_array, $key)
    {
        return isset($meta_array[$key]) ? esc_attr($meta_array[$key][0]) : '';
    }

    $gallery_images = get_post_meta( $post->ID, 'gallery_images', true );

    // Start output buffer to make it easier to manipulate the HTML structure if needed
    ob_start();
?>
    <style>
        .lavori-pubblici-meta-box p {
            margin-bottom: 20px;
        }

        .lavori-pubblici-meta-box label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .lavori-pubblici-meta-box input[type="text"],
        .lavori-pubblici-meta-box input[type="date"],
        .lavori-pubblici-meta-box select,
        .lavori-pubblici-meta-box textarea {
            width: 100%;
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .lavori-pubblici-meta-box textarea {
            height: 120px;
            resize: vertical;
        }

        .lavori-pubblici-gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .lavori-pubblici-gallery img {
            max-width: 100px;
            height: auto;
            display: block;
        }

        .lavori-pubblici-gallery .gallery-item {
            position: relative;
        }

        .lavori-pubblici-gallery .gallery-item .remove-image {
            position: absolute;
            top: -10px;
            right: -10px;
            background: #ff0000;
            color: #fff;
            padding: 2px 6px;
            font-size: 12px;
            cursor: pointer;
        }
    </style>

    <div class="lavori-pubblici-meta-box">
        <p>
            <label for="importo_appalto"><?php _e('Importo Appalto', 'limbiate') ?></label>
            <input type="text" name="importo_appalto" id="importo_appalto" value="<?php echo get_meta_value($lavori_pubblici_stored_meta, 'importo_appalto'); ?>" />
        </p>

        <p>
            <label for="origine_finanziamento"><?php _e('Origine del Finanziamento', 'limbiate') ?></label>
            <input type="text" name="origine_finanziamento" id="origine_finanziamento" value="<?php echo get_meta_value($lavori_pubblici_stored_meta, 'origine_finanziamento'); ?>" />
        </p>

        <p>
            <label for="indirizzo_esatto"><?php _e('Indirizzo Esatto', 'limbiate') ?></label>
            <input type="text" name="indirizzo_esatto" id="indirizzo_esatto" value="<?php echo get_meta_value($lavori_pubblici_stored_meta, 'indirizzo_esatto'); ?>" />
        </p>
        <?php $actualQuartiere = get_meta_value($lavori_pubblici_stored_meta, 'quartiere'); ?>
        <p>
            <label for="quartiere"><?php _e('Quartiere', 'limbiate') ?></label>
            <select name="quartiere" id="quartiere">
                <option value="sanfrancesco" <?php echo $actualQuartiere === 'sanfrancesco' ? 'selected' : ''; ?>>Quartiere San Francesco</option>
                <option value="ceresolo" <?php echo $actualQuartiere === 'ceresolo' ? 'selected' : ''; ?>>Quartiere Ceresolo</option>
                <option value="giovi" <?php echo $actualQuartiere === 'giovi' ? 'selected' : ''; ?>>Villaggio dei Giovi</option>
                <option value="fiori" <?php echo $actualQuartiere === 'fiori' ? 'selected' : ''; ?>>Villaggio dei Fiori</option>
                <option value="sole" <?php echo $actualQuartiere === 'sole' ? 'selected' : ''; ?>>Villaggio del Sole</option>
            </select>
        </p>

        <p>
            <label for="data_inizio_lavori"><?php _e('Data Inizio Lavori', 'limbiate') ?></label>
            <input type="date" name="data_inizio_lavori" id="data_inizio_lavori" value="<?php echo get_meta_value($lavori_pubblici_stored_meta, 'data_inizio_lavori'); ?>" />
        </p>

        <p>
            <label for="breve_descrizione"><?php _e('Breve Descrizione', 'limbiate') ?></label>
            <textarea name="breve_descrizione" id="breve_descrizione"><?php echo get_meta_value($lavori_pubblici_stored_meta, 'breve_descrizione'); ?></textarea>
        </p>

        <p>
            <label for="gallery_images"><?php _e('Galleria Immagini', 'textdomain'); ?></label>
            <button type="button" id="upload_gallery_button" class="button"><?php _e('Aggiungi o Modifica Immagini', 'textdomain'); ?></button>
            <input type="hidden" id="gallery_images" name="gallery_images" value="<?php echo esc_attr($gallery_images); ?>" />
        </p>

        <div id="lavori_pubblici_gallery" class="lavori-pubblici-gallery">
            <?php
            if (! empty($gallery_images)) {
                $images = explode(',', $gallery_images);
                foreach ($images as $image_id) {
                    $image_url = wp_get_attachment_image_src($image_id, 'thumbnail');
                    echo '<div class="gallery-item"><img src="' . esc_url($image_url[0]) . '" /><span class="remove-image" data-id="' . esc_attr($image_id) . '">&times;</span></div>';
                }
            }
            ?>
        </div>

    </div>

    <script>
        jQuery(document).ready(function($) {
            var frame;
            var galleryImages = $('#gallery_images');

            $('#upload_gallery_button').on('click', function(event) {
                event.preventDefault();

                if (frame) {
                    frame.open();
                    return;
                }

                frame = wp.media({
                    title: '<?php _e("Seleziona o Carica Immagini", "textdomain"); ?>',
                    button: {
                        text: '<?php _e("Usa queste immagini", "textdomain"); ?>'
                    },
                    multiple: true
                });

                frame.on('select', function() {
                    var attachments = frame.state().get('selection').map(function(attachment) {
                        attachment = attachment.toJSON();
                        return attachment.id;
                    });

                    var attachment_ids = attachments.join(',');
                    galleryImages.val(attachment_ids);

                    // Display selected images
                    var galleryContainer = $('#lavori_pubblici_gallery');
                    galleryContainer.empty();

                    attachments.forEach(function(id) {
                        var img = wp.media.attachment(id).get('sizes').thumbnail.url;
                        galleryContainer.append('<div class="gallery-item"><img src="' + img + '" /><span class="remove-image" data-id="' + id + '">&times;</span></div>');
                    });
                });

                frame.open();
            });

            // Remove image from gallery
            $('#lavori_pubblici_gallery').on('click', '.remove-image', function() {
                var id = $(this).data('id');
                var image_ids = galleryImages.val().split(',');

                // Remove the clicked image ID from the array
                image_ids = image_ids.filter(function(item) {
                    return item != id;
                });

                galleryImages.val(image_ids.join(','));

                // Remove the image from the DOM
                $(this).parent().remove();
            });
        });
    </script>

<?php

    // Output the buffer content
    echo ob_get_clean();
}


function save_lavori_pubblici_meta($post_id)
{
    // Check if nonce is set.
    if (! isset($_POST['lavori_pubblici_nonce'])) {
        return $post_id;
    }
    $nonce = $_POST['lavori_pubblici_nonce'];

    // Verify that the nonce is valid.
    if (! wp_verify_nonce($nonce, basename(__FILE__))) {
        return $post_id;
    }

    // Check if this is an autosave.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    // Check the user's permissions.
    if ('lavori_pubblici' == $_POST['post_type']) {
        if (! current_user_can('edit_post', $post_id)) {
            return $post_id;
        }
    } else {
        if (! current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    }

    if (isset($_POST['gallery_images'])) {
        update_post_meta($post_id, 'gallery_images', sanitize_text_field($_POST['gallery_images']));
    }

    // Sanitize user input.
    $importo_appalto = sanitize_text_field($_POST['importo_appalto']);
    $origine_finanziamento = sanitize_text_field($_POST['origine_finanziamento']);
    $indirizzo_esatto = sanitize_text_field($_POST['indirizzo_esatto']);
    $quartiere = sanitize_text_field($_POST['quartiere']);
    $data_inizio_lavori = sanitize_text_field($_POST['data_inizio_lavori']);
    $breve_descrizione = sanitize_text_field($_POST['breve_descrizione']);
    // $tipologia_lavori = sanitize_text_field( $_POST['tipologia_lavori'] );

    // Update the meta field in the database.
    update_post_meta($post_id, 'importo_appalto', $importo_appalto);
    update_post_meta($post_id, 'origine_finanziamento', $origine_finanziamento);
    update_post_meta($post_id, 'indirizzo_esatto', $indirizzo_esatto);
    update_post_meta($post_id, 'quartiere', $quartiere);
    update_post_meta($post_id, 'data_inizio_lavori', $data_inizio_lavori);
    update_post_meta($post_id, 'breve_descrizione', $breve_descrizione);
    // update_post_meta( $post_id, 'tipologia_lavori', $tipologia_lavori );
}
add_action('save_post', 'save_lavori_pubblici_meta');
