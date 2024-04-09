<?php

function map_add_admin_page() {
    // add_menu_page(
    //     'Mappa zone', // Page title
    //     'Mappa zone', // Menu title
    //     'manage_options', // Capability required to see this menu item
    //     'map-admin-page', // Menu slug, used to uniquely identify the page
    //     'map_admin_page_content', // Function that outputs the content of the page
    //     'dashicons-location', // Icon URL
    //     6 // Position in the menu. 
    // );
    add_submenu_page(
        'dci_options', // Parent slug
        'Mappa zone',                // Page title
        'Mappa zone',                // Menu title
        'manage_options',            // Capability required
        'map-admin-page',            // Menu slug
        'map_admin_page_content'     // Function to display the submenu page content
    );
    // toplevel_page_dci_options
}
add_action('admin_menu', 'map_add_admin_page', 99);

function map_admin_page_content() {
    // Get existing data
    $saved_data = get_option('limbiate_map_admin_page_data');
    if (!is_array($saved_data)) {
        $saved_data = ['svg_viewbox' => '', 'box_width' => '', 'box_height' => '', 'map_data' => []];
    }
    $map_data = $saved_data['map_data'];

    // Get categories for dropdown
    $categories = get_categories(array('hide_empty' => 0));
    ?>

    <div class="wrap">
        <h1>Mappa</h1>
        <br>
        <p>Utilizza lo shortcode <code>[limbiate-zone-map]</code> per mostrare la mappa in una pagina.</p>
        <form method="post" action="">
            <label>
                SVG Viewbox:
                <input type="text" name="svg_viewbox" value="<?php echo esc_attr($saved_data['svg_viewbox']); ?>" />
            </label> &nbsp;&nbsp;
            <label>
                Container:
                <input type="number" placeholder="Larghezza" name="box_width" value="<?php echo esc_attr($saved_data['box_width']); ?>" style="width: 80px" />
            </label>
            <label>
                x
                <input type="number" placeholder="Altezza" name="box_height" value="<?php echo esc_attr($saved_data['box_height']); ?>" style="width: 80px" />
            </label>
            <br>
            <table class="form-table" id="map-table">
                <thead>
                    <tr>
                        <th>Nome Zona</th>
                        <th>Categoria</th>
                        <th>Immagine</th>
                        <th>Immagine hover</th>
                        <th>SVG Path D</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $totRows = 10;
                    for ($i = 0; $i < $totRows; $i++): ?>
                        <tr>
                            <td><input type="text" name="map_data[<?php echo $i; ?>][name]" value="<?php echo esc_attr($map_data[$i]['name']); ?>" /></td>
                            <td>
                                <select name="map_data[<?php echo $i; ?>][category]">
                                    <option value="">Seleziona una categoria</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo esc_attr($category->term_id); ?>" <?php selected($map_data[$i]['category'], $category->term_id); ?>>
                                            <?php echo esc_html($category->name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <input type="hidden" name="map_data[<?php echo $i; ?>][image_off]" class="map-image-off-id" value="<?php echo esc_attr($map_data[$i]['image_off']); ?>" />
                                <img src="<?php echo wp_get_attachment_url($map_data[$i]['image_off']); ?>" class="map-preview-image-off" style="max-width: 60px; max-height: 60px;" />
                                <button type="button" class="button map-upload-image-off-button">Carica</button>
                            </td>
                            <td>
                                <input type="hidden" name="map_data[<?php echo $i; ?>][image_on]" class="map-image-on-id" value="<?php echo esc_attr($map_data[$i]['image_on']); ?>" />
                                <img src="<?php echo wp_get_attachment_url($map_data[$i]['image_on']); ?>" class="map-preview-image-on" style="max-width: 60px; max-height: 60px;" />
                                <button type="button" class="button map-upload-image-on-button">Carica</button>
                            </td>
                            <td><textarea style="width:200px;" rows="3" type="text" name="map_data[<?php echo $i; ?>][path_d]"><?php echo esc_attr($map_data[$i]['path_d']); ?></textarea></td>
                            <td>
                                <button type="button" class="button map-clear-row-button">Pulisci riga</button>
                            </td>
                        </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
            <p class="submit">
                <input type="submit" name="map_save_data" id="submit" class="button button-primary" value="Salva">
            </p>
        </form>
    </div>
    <?php
}

function map_admin_scripts() {
    if (!did_action('wp_enqueue_media')) {
        wp_enqueue_media();
    }
    wp_enqueue_script('zone-map-admin-script', get_template_directory_uri() . '/zones-map/zone-map-admin.js', array('jquery'), null, true);
}
add_action('admin_enqueue_scripts', 'map_admin_scripts');

function map_save_admin_page_data() {
    // name, category, image_on, image_off, path_d
    if (isset($_POST['map_save_data'])) {
        $svg_viewbox = $_POST['svg_viewbox'];
        $box_width = $_POST['box_width'];
        $box_height = $_POST['box_height'];
        $map_data = $_POST['map_data'];
        foreach ($map_data as $k => $v) {
            if (empty($v['category']) && empty($v['name'])) {
                unset($map_data[$k]);
            }
        }
        update_option('limbiate_map_admin_page_data', [
            'box_width' => $box_width,
            'box_height' => $box_height,
            'svg_viewbox' => $svg_viewbox, 
            'map_data' => $map_data 
        ]);
    }
}
add_action('admin_init', 'map_save_admin_page_data');

function limbiate_zone_map_shortcode() {
    wp_enqueue_script('zone-map-js', get_template_directory_uri() . '/zones-map/zone-map.js', array('jquery'), null, true);
    wp_enqueue_style('zone-map-css', get_template_directory_uri() . '/zones-map/zone-map.css');

    ob_start();

    $option_data = get_option('limbiate_map_admin_page_data', array());
    $map_data = $option_data['map_data'];
    $width = $option_data['box_width'] ?? 800;
    $height = $option_data['box_height'] ?? 800;
    $svg_viewbox = $option_data['svg_viewbox'];

    echo '<div id="limbiate-zone-map-container" style="width: ' . $width . 'px; height: ' . $height . 'px">';
    // Output your map images here, ensure they have a common class for targeting
    foreach ($map_data as $x => $zone) {
        echo '<img src="' . wp_get_attachment_url($zone['image_off']) . '"' .
        ' data-img-type="off" ' . 
        ' class="limbiate-zone-image" data-zone="' . $x . '">';
        echo '<img src="' . wp_get_attachment_url($zone['image_on']) . '"' .
        ' data-img-type="on" ' . 
        ' style="display:none" ' . 
        ' class="limbiate-zone-image" data-zone="' . $x . '">';
    }
    // Output the SVG overlay
    echo '<svg class="limbiate-zone-svg" viewBox="'. $svg_viewbox .'">'; 
    foreach ($map_data as $x => $zone) {
        echo '<path id="zone' . $x . '" data-zone="' . $x . '" d="' . $zone['path_d'] . '" />'; // The 'd' attribute holds the path data
    }
    echo '</svg>';
    echo '</div>';

    return ob_get_clean();
}
add_shortcode('limbiate-zone-map', 'limbiate_zone_map_shortcode');

function get_category_post_count() {
    $category_id = intval($_POST['category_id']);
    if ($category_id) {
        $posts = get_posts(array(
            'category' => $category_id,
            'post_status' => 'publish',
            'numberposts' => -1
        ));
        wp_send_json_success(count($posts));
    } else {
        wp_send_json_error('Category not found.');
    }
}
add_action('wp_ajax_get_category_post_count', 'get_category_post_count');
add_action('wp_ajax_nopriv_get_category_post_count', 'get_category_post_count');
