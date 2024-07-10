<?php

add_action('cmb2_init', 'dci_add_post_metaboxes');
function dci_add_post_metaboxes()
{
    $prefix = '_dci_post_';

    //DOCUMENTI
    $cmb_documenti = new_cmb2_box(array(
        'id'           => $prefix . 'box_documenti',
        'title'        => __('Documenti', 'design_comuni_italia'),
        'object_types' => array('post'),
        'context'      => 'normal',
        'priority'     => 'low',
    ));

    $cmb_documenti->add_field(array(
        'id' => $prefix . 'allegati',
        'name'        => __('Allegati', 'design_comuni_italia'),
        'desc' => __('Elenco di documenti allegati alla struttura', 'design_comuni_italia'),
        'type' => 'file_list',
    ));
}

/**
 * Valorizzo il post content in base al contenuto dei campi custom
 * @param $data
 * @return mixed
 */
function dci_post_set_post_content($data)
{
    return $data;
}
add_filter('wp_insert_post_data', 'dci_post_set_post_content', '99', 1);
