<?php

/**
 * Definisce la tassonomia Stagioni Evento
 */
add_action( 'init', 'dci_register_taxonomy_stagioni_evento', -10 );
function dci_register_taxonomy_stagioni_evento() {

    $labels = array(
        'name'              => _x( 'Stagioni di eventi', 'taxonomy general name', 'design_comuni_italia' ),
        'singular_name'     => _x( 'Stagioni di eventi', 'taxonomy singular name', 'design_comuni_italia' ),
        'search_items'      => __( 'Cerca Stagione di eventi', 'design_comuni_italia' ),
        'all_items'         => __( 'Tutte le Stagioni di eventi', 'design_comuni_italia' ),
        'edit_item'         => __( 'Modifica la Stagione di eventi', 'design_comuni_italia' ),
        'update_item'       => __( 'Aggiorna la Stagione di eventi', 'design_comuni_italia' ),
        'add_new_item'      => __( 'Aggiungi una Stagione di eventi', 'design_comuni_italia' ),
        'new_item_name'     => __( 'Nuova Stagione di eventi', 'design_comuni_italia' ),
        'menu_name'         => __( 'Stagioni di Eventi', 'design_comuni_italia' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'public'            => false, //enable to get term archive page
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'has_archive'           => false,    //archive page
        //'rewrite'           => array( 'slug' => 'tipo-evento' ),
        'capabilities'      => array(
            'manage_terms'  => 'manage_tipi_evento',
            'edit_terms'    => 'edit_tipi_evento',
            'delete_terms'  => 'delete_tipi_evento',
            'assign_terms'  => 'assign_tipi_evento'
        )
    );

    register_taxonomy( 'stagioni_evento', array( 'evento' ), $args );
}

// Show custom field when editing a term
add_action('stagioni_evento_edit_form_fields', 'dci_add_is_current_field', 10, 2);
function dci_add_is_current_field($term, $taxonomy) {
    $is_current = get_term_meta($term->term_id, 'is_current', true);
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="is_current"><?php _e("È in corso", 'design_comuni_italia'); ?></label></th>
        <td>
            <input type="checkbox" name="is_current" id="is_current" value="1" <?php checked($is_current, 1); ?> />
            <p class="description"><?php _e("Spunta se la stagione è quella attuale.", 'design_comuni_italia'); ?></p>
        </td>
    </tr>
    <?php
}

// Show custom field when adding a new term
add_action('stagioni_evento_add_form_fields', 'dci_add_is_current_field_new', 10, 2);
function dci_add_is_current_field_new($taxonomy) {
    ?>
    <div class="form-field">
        <label for="is_current"><?php _e("È in corso", 'design_comuni_italia'); ?></label>
        <input type="checkbox" name="is_current" id="is_current" value="1" />
        <p class="description"><?php _e("Spunta se la stagione è quella attuale.", 'design_comuni_italia'); ?></p>
    </div>
    <?php
}

// Save the value on edit
add_action('edited_stagioni_evento', 'dci_save_is_current_meta', 10, 2);
// Save the value on add
add_action('created_stagioni_evento', 'dci_save_is_current_meta', 10, 2);

function dci_save_is_current_meta($term_id, $tt_id) {
    $value = isset($_POST['is_current']) ? 1 : 0;
    update_term_meta($term_id, 'is_current', $value);
}

add_filter('manage_edit-stagioni_evento_columns', 'dci_add_is_current_column');
function dci_add_is_current_column($columns) {
    $columns['is_current'] = __('È in corso', 'design_comuni_italia');
    return $columns;
}

add_filter('manage_stagioni_evento_custom_column', 'dci_show_is_current_column', 10, 3);
function dci_show_is_current_column($content, $column_name, $term_id) {
    if ($column_name === 'is_current') {
        $is_current = get_term_meta($term_id, 'is_current', true);
        $content = $is_current ? '✅' : '—';
    }
    return $content;
}

add_action('admin_head', function() {
    echo '<style>#stagioni_eventodiv { display: none !important; }</style>';
});